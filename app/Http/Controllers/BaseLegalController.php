<?php

namespace App\Http\Controllers;


use App\Helper\Files;
use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\Jobs\ImportProductJob;
use App\Models\Document;
use App\Models\Source;
use App\Models\Thematique;
use App\DataTables\ProductsDataTable;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Admin\Employee\ImportRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Admin\Employee\ImportProcessRequest;
use App\Traits\ImportExcel;

class BaseLegalController extends AccountBaseController
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

    /**
     * Dashboard principal de la base légale
     */
    public function index()
    {
        $this->stats = [
            'totalDocuments' => Document::actif()->count(),
            'totalThematiques' => Thematique::count(),
            'totalSources' => Source::count(),
            'documentsInactifs' => Document::where('actif', false)->count(),
        ];

        $this->recentDocuments = Document::with(['source', 'thematiques'])
            ->actif()
            ->latest()
            ->take(5)
            ->get();

        $this->topThematiques = Thematique::withCount('documents')
            ->orderBy('documents_count', 'desc')
            ->take(5)
            ->get();

        $this->sourceStats = Source::withCount('documents')
            ->get()
            ->groupBy('type')
            ->map(function ($sources) {
                return [
                    'count' => $sources->count(),
                    'documents' => $sources->sum('documents_count')
                ];
            });
     
         return  view('base-legal.dashboard',$this->data);

    
    }

    /**
     * Interface de consultation publique
     */
    public function show(Request $request)
    {
        $query = Document::with(['source', 'thematiques'])->actif();

        // Filtres
        if ($request->filled('thematique')) {
            $query->parThematique($request->thematique);
        }

        if ($request->filled('source_type')) {
            $query->parSource($request->source_type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $this->documents = $query->latest('date_publication')->paginate(12);
        $this->thematiques = Thematique::withCount('documents')->get();
        $this->sourceTypes = Source::select('type')->distinct()->get();

        return view('base-legal.consultation',$this->data);
    }

    // ================================
    // GESTION DES DOCUMENTS
    // ================================

    /**
     * Liste des documents (admin)
     */
    public function documentsIndex(Request $request)
    {
        $query = Document::with(['source', 'thematiques']);

        // Filtres admin
        if ($request->filled('thematique')) {
            $query->parThematique($request->thematique);
        }

        if ($request->filled('source_type')) {
            $query->parSource($request->source_type);
        }

        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->actif);
        }

        $documents = $query->latest()->paginate(15);
        $thematiques = Thematique::all();
        $sources = Source::all();

        return view('base-legal.documents.index', compact('documents', 'thematiques', 'sources'));
    }

    /**
     * Afficher le formulaire de création d'un document
     */
    public function documentsCreate()
    {
        $sources = Source::all();
        $thematiques = Thematique::all();
        
        return view('base-legal.documents.create', compact('sources', 'thematiques'));
    }

    /**
     * Enregistrer un nouveau document
     */
    public function documentsStore(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|max:255',
            'description' => 'nullable',
            'fichier_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'url_externe' => 'nullable|url',
            'date_publication' => 'nullable|date',
            'source_id' => 'required|exists:sources,id',
            'thematiques' => 'required|array',
            'thematiques.*' => 'exists:thematiques,id',
            'actif' => 'boolean'
        ]);

        if ($request->hasFile('fichier_pdf')) {
            $validated['fichier_pdf'] = $request->file('fichier_pdf')->store('documents', 'public');
        }

        $validated['actif'] = $request->has('actif');

        $document = Document::create($validated);
        $document->thematiques()->sync($request->thematiques);

        return redirect()->route('base-legal.documents.index')
            ->with('success', 'Document créé avec succès');
    }

    /**
     * Afficher un document
     */
    public function documentsShow(Document $document)
    {
        $document->load(['source', 'thematiques']);
        return view('base-legal.documents.show', compact('document'));
    }

    /**
     * Afficher le formulaire d'édition d'un document
     */
    public function documentsEdit(Document $document)
    {
        $sources = Source::all();
        $thematiques = Thematique::all();
        
        return view('base-legal.documents.edit', compact('document', 'sources', 'thematiques'));
    }

    /**
     * Mettre à jour un document
     */
    public function documentsUpdate(Request $request, Document $document)
    {
        $validated = $request->validate([
            'titre' => 'required|max:255',
            'description' => 'nullable',
            'fichier_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'url_externe' => 'nullable|url',
            'date_publication' => 'nullable|date',
            'source_id' => 'required|exists:sources,id',
            'thematiques' => 'required|array',
            'thematiques.*' => 'exists:thematiques,id',
            'actif' => 'boolean'
        ]);

        if ($request->hasFile('fichier_pdf')) {
            // Supprimer l'ancien fichier
            if ($document->fichier_pdf) {
                Storage::disk('public')->delete($document->fichier_pdf);
            }
            $validated['fichier_pdf'] = $request->file('fichier_pdf')->store('documents', 'public');
        }

        $validated['actif'] = $request->has('actif');

        $document->update($validated);
        $document->thematiques()->sync($request->thematiques);

        return redirect()->route('base-legal.documents.index')
            ->with('success', 'Document mis à jour avec succès');
    }

    /**
     * Supprimer un document
     */
    public function documentsDestroy(Document $document)
    {
        if ($document->fichier_pdf) {
            Storage::disk('public')->delete($document->fichier_pdf);
        }
        
        $document->delete();

        return redirect()->route('base-legal.documents.index')
            ->with('success', 'Document supprimé avec succès');
    }

    // ================================
    // GESTION DES THÉMATIQUES
    // ================================

    /**
     * Liste des thématiques
     */
    public function thematiquesIndex()
    {
        $thematiques = Thematique::withCount('documents')->get();
        return view('base-legal.thematiques.index', compact('thematiques'));
    }

    /**
     * Formulaire de création d'une thématique
     */
    public function thematiquesCreate()
    {
        return view('base-legal.thematiques.create');
    }

    /**
     * Enregistrer une nouvelle thématique
     */
    public function thematiquesStore(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|max:255|unique:thematiques',
            'description' => 'nullable'
        ]);

        Thematique::create($validated);

        return redirect()->route('base-legal.thematiques.index')
            ->with('success', 'Thématique créée avec succès');
    }

    /**
     * Afficher une thématique et ses documents
     */
    public function thematiquesShow(Thematique $thematique, Request $request)
    {
        $query = $thematique->documents()->with('source')->actif();

        if ($request->filled('source_type')) {
            $query->parSource($request->source_type);
        }

        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        $documents = $query->latest('date_publication')->paginate(10);
        $sourceTypes = $thematique->documents()
            ->join('sources', 'documents.source_id', '=', 'sources.id')
            ->select('sources.type')
            ->distinct()
            ->pluck('type');

        return view('base-legal.thematiques.show', compact('thematique', 'documents', 'sourceTypes'));
    }

    /**
     * Formulaire d'édition d'une thématique
     */
    public function thematiquesEdit(Thematique $thematique)
    {
        return view('base-legal.thematiques.edit', compact('thematique'));
    }

    /**
     * Mettre à jour une thématique
     */
    public function thematiquesUpdate(Request $request, Thematique $thematique)
    {
        $validated = $request->validate([
            'nom' => 'required|max:255|unique:thematiques,nom,' . $thematique->id,
            'description' => 'nullable'
        ]);

        $thematique->update($validated);

        return redirect()->route('base-legal.thematiques.index')
            ->with('success', 'Thématique mise à jour avec succès');
    }

    /**
     * Supprimer une thématique
     */
    public function thematiquesDestroy(Thematique $thematique)
    {
        $thematique->delete();

        return redirect()->route('base-legal.thematiques.index')
            ->with('success', 'Thématique supprimée avec succès');
    }

    // ================================
    // GESTION DES SOURCES
    // ================================

    /**
     * Liste des sources
     */
    public function sourcesIndex()
    {
        $sources = Source::withCount('documents')->get();
        return view('base-legal.sources.index', compact('sources'));
    }

    /**
     * Formulaire de création d'une source
     */
    public function sourcesCreate()
    {
        $types = Source::TYPES;
        return view('base-legal.sources.create', compact('types'));
    }

    /**
     * Enregistrer une nouvelle source
     */
    public function sourcesStore(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Source::TYPES))
        ]);

        Source::create($validated);

        return redirect()->route('base-legal.sources.index')
            ->with('success', 'Source créée avec succès');
    }

    /**
     * Afficher une source et ses documents
     */
    public function sourcesShow(Source $source, Request $request)
    {
        $query = $source->documents()->with('thematiques')->actif();

        if ($request->filled('thematique')) {
            $query->parThematique($request->thematique);
        }

        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        $documents = $query->latest('date_publication')->paginate(10);
        $thematiques = $source->documents()
            ->join('document_thematique', 'documents.id', '=', 'document_thematique.document_id')
            ->join('thematiques', 'document_thematique.thematique_id', '=', 'thematiques.id')
            ->select('thematiques.nom', 'thematiques.slug')
            ->distinct()
            ->get();

        return view('base-legal.sources.show', compact('source', 'documents', 'thematiques'));
    }

    /**
     * Formulaire d'édition d'une source
     */
    public function sourcesEdit(Source $source)
    {
        $types = Source::TYPES;
        return view('base-legal.sources.edit', compact('source', 'types'));
    }

    /**
     * Mettre à jour une source
     */
    public function sourcesUpdate(Request $request, Source $source)
    {
        $validated = $request->validate([
            'nom' => 'required|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Source::TYPES))
        ]);

        $source->update($validated);

        return redirect()->route('base-legal.sources.index')
            ->with('success', 'Source mise à jour avec succès');
    }

    /**
     * Supprimer une source
     */
    public function sourcesDestroy(Source $source)
    {
        $source->delete();

        return redirect()->route('base-legal.sources.index')
            ->with('success', 'Source supprimée avec succès');
    }

    // ================================
    // MÉTHODES UTILITAIRES / API
    // ================================

    /**
     * Recherche AJAX pour documents
     */
    public function searchDocuments(Request $request)
    {
        $query = Document::with(['source', 'thematiques'])
            ->actif()
            ->where('titre', 'like', '%' . $request->q . '%')
            ->take(10);

        return response()->json($query->get());
    }

    /**
     * Basculer le statut actif/inactif d'un document
     */
    public function toggleDocumentStatus(Document $document)
    {
        $document->update(['actif' => !$document->actif]);

        return response()->json([
            'success' => true,
            'status' => $document->actif ? 'actif' : 'inactif'
        ]);
    }

    /**
     * Statistiques pour le dashboard
     */
    public function getStats()
    {
        return response()->json([
            'documents' => Document::actif()->count(),
            'thematiques' => Thematique::count(),
            'sources' => Source::count(),
            'recent_documents' => Document::actif()->latest()->take(3)->get()
        ]);
    }
}
