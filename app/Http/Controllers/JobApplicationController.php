<?php

namespace App\Http\Controllers;


use App\Helper\Files;
use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\Jobs\ImportProductJob;
use App\DataTables\ProductsDataTable;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Admin\Employee\ImportRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Admin\Employee\ImportProcessRequest;
use App\Traits\ImportExcel;

use App\Models\JobOffer;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends AccountBaseController
{
    use ImportExcel;
   // ================================
    // DASHBOARD & VUES GÉNÉRALES
    // ================================

     public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Base Légale';
        // $this->middleware(
        //     function ($request, $next) {
        //         in_array('client', user_roles()) ? abort_403(!(in_array('orders', $this->user->modules) && user()->permission('add_order') == 'all')) : abort_403(!in_array('products', $this->user->modules));

        //         return $next($request);
        //     }
        // );
    }

    public function index(Request $request)
    {
        $query = JobApplication::with(['jobOffer', 'workflows.assignedUser']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job_offer_id')) {
            $query->where('job_offer_id', $request->job_offer_id);
        }

        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $this->applications = $query->orderBy('created_at', 'desc')->paginate(15);

        $this->jobOffers = JobOffer::active()->get();
        $this->statuses = ['pending', 'reviewing', 'shortlisted', 'interview_scheduled', 'interviewed', 'test_assigned', 'test_completed', 'accepted', 'rejected'];

        return view('hr.applications.index', $this->data);
    }

    public function show(JobApplication $application)
    {
       $this->application = $application->load(['jobOffer', 'workflows.assignedUser', 'interviews.interviewer']);
        
        return view('hr.applications.show', $this->data);
    }


    // Candidature publique


    public function showApplyForm(JobOffer $jobOffer){
        
    $this->jobOffer = $jobOffer;
    // Vérifier que l'offre est active et dans les délais
    if ($jobOffer->status !== 'active' || $jobOffer->deadline < now()) {
        // return redirect()->route('public.job-offers.show', $jobOffer)
        //                 ->with('error', 'Cette offre d\'emploi n\'est plus disponible pour les candidatures.');
    }

    return view('public.job-offers.apply', $this->data);
}

    public function apply(Request $request, JobOffer $jobOffer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'message' => 'nullable|string',
            'linkedin_profile' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'experience' => 'nullable|array',
            'education' => 'nullable|array',
            'skills' => 'nullable|array'
        ]);

        // Vérifier si candidature existe déjà
        $existingApplication = JobApplication::where('job_offer_id', $jobOffer->id)
                                            ->where('email', $validated['email'])
                                            ->first();

        if ($existingApplication) {
            return redirect()->back()
                            ->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        // Upload des fichiers
        if ($request->hasFile('cv')) {
            $validated['cv_path'] = $request->file('cv')->store('applications/cv', 'public');
        }

        if ($request->hasFile('cover_letter')) {
            $validated['cover_letter_path'] = $request->file('cover_letter')->store('applications/cover_letters', 'public');
        }

        $validated['job_offer_id'] = $jobOffer->id;

        $application = JobApplication::create($validated);

        // Créer le workflow initial
        $application->workflows()->create([
            'stage' => 'Candidature reçue',
            'description' => 'Candidature soumise en ligne',
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->route('public.job-offers.show', $jobOffer)
                        ->with('success', 'Votre candidature a été soumise avec succès!');
    }

    public function downloadCV(JobApplication $application)
    {
        if (!$application->cv_path || !Storage::disk('public')->exists($application->cv_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($application->cv_path, $application->full_name . '_CV.pdf');
    }

    public function downloadCoverLetter(JobApplication $application)
    {
        if (!$application->cover_letter_path || !Storage::disk('public')->exists($application->cover_letter_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($application->cover_letter_path, $application->full_name . '_Lettre_Motivation.pdf');
    }
    /**
 * Mise à jour du statut d'une candidature
 */
public function updateStatus(Request $request, JobApplication $application)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,reviewing,shortlisted,interview_scheduled,interviewed,test_assigned,test_completed,accepted,rejected',
        'notes' => 'nullable|string|max:1000'
    ]);

    $application->update([
        'status' => $validated['status'],
        'updated_at' => now()
    ]);

    // Créer une nouvelle étape de workflow
    $application->workflows()->create([
        'stage' => ucfirst(str_replace('_', ' ', $validated['status'])),
        'description' => 'Statut mis à jour : ' . $validated['status'],
        'status' => 'completed',
        'completed_at' => now(),
        'assigned_to' => Auth::id(),
        'notes' => $validated['notes'] ?? null
    ]);

    // Notification par email au candidat (optionnelle)
    // Mail::to($application->email)->send(new StatusUpdated($application));

    return redirect()->back()
                    ->with('success', 'Statut de la candidature mis à jour avec succès !');
}

/**
 * Mise à jour de l'évaluation d'une candidature
 */
public function updateRating(Request $request, JobApplication $application)
{
    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'notes' => 'nullable|string|max:1000'
    ]);

    $application->update([
        'rating' => $validated['rating'],
        'notes' => $validated['notes'],
        'updated_at' => now()
    ]);

    // Créer une étape de workflow pour l'évaluation
    $application->workflows()->create([
        'stage' => 'Évaluation',
        'description' => 'Candidature évaluée : ' . $validated['rating'] . '/5 étoiles',
        'status' => 'completed',
        'completed_at' => now(),
        'assigned_to' => Auth::id(),
        'notes' => $validated['notes'] ?? null
    ]);

    return redirect()->back()
                    ->with('success', 'Évaluation mise à jour avec succès !');
}

}
