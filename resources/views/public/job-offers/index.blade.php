{{-- resources/views/public/job-offers/index.blade.php --}}
@extends('layouts.public')

@section('content')
<div class="tw-min-h-screen tw-bg-gray-50">
    {{-- Hero Section --}}
    <div class="tw-bg-gradient-to-r tw-from-orange-400 tw-to-orange-600 tw-py-5">
        <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-text-center">
                <h1 class="tw-text-xl md:tw-text-2xl tw-font-bold tw-text-white tw-mb-4">
                    Rejoignez Notre Équipe
                </h1>
                <p class="tw-text-sm md:tw-text-base tw-text-orange-100 tw-max-w-3xl tw-mx-auto">
                    Découvrez nos opportunités de carrière et trouvez le poste qui correspond à vos ambitions
                </p>
                
            </div>
        </div>
    </div>

    {{-- Liste des offres --}}
    <div class="tw-py-12">
        <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
            @if($jobOffers->count() > 0)
                <div class="tw-mb-8">
                    <h2 class="tw-text-2xl tw-font-bold tw-text-gray-900 tw-mb-2">
                        Offres Disponibles
                    </h2>
                    <p class="tw-text-gray-600">{{ $jobOffers->total() }} offre(s) d'emploi disponible(s)</p>
                </div>

                <div class="tw-grid tw-grid-cols-1 tw-gap-6 lg:tw-grid-cols-2 xl:tw-grid-cols-3">
                    @foreach($jobOffers as $jobOffer)
                        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-overflow-hidden hover:tw-shadow-md tw-transition tw-duration-200">
                            <div class="tw-p-6">
                                {{-- En-tête de la carte --}}
                                <div class="tw-flex tw-items-start tw-justify-between tw-mb-4">
                                    <div class="tw-flex-1">
                                        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 tw-mb-1">
                                            {{ $jobOffer->title }}
                                        </h3>
                                        <p class="tw-text-sm tw-text-gray-600">{{ $jobOffer->department }}</p>
                                    </div>
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-orange-100 tw-text-orange-800">
                                        {{ $jobOffer->type }}
                                    </span>
                                </div>

                                {{-- Localisation --}}
                                <div class="tw-flex tw-items-center tw-text-sm tw-text-gray-600 tw-mb-4">
                                    <i class="fas fa-map-marker-alt tw-mr-2 tw-text-orange-400"></i>
                                    {{ $jobOffer->location }}
                                </div>

                                {{-- Description courte --}}
                                <p class="tw-text-gray-700 tw-text-sm tw-mb-4 tw-line-clamp-3">
                                    {{ Str::limit($jobOffer->description, 150) }}
                                </p>

                                {{-- Informations additionnelles --}}
                                <div class="tw-flex tw-items-center tw-justify-between tw-text-xs tw-text-gray-500 tw-mb-4">
                                    <span>{{ $jobOffer->positions_available }} poste(s)</span>
                                    @if($jobOffer->salary_range)
                                        <span>{{ $jobOffer->salary_range }}</span>
                                    @endif
                                </div>

                                {{-- Date limite --}}
                                <div class="tw-flex tw-items-center tw-justify-between tw-border-t tw-border-gray-200 tw-pt-4">
                                    <span class="tw-text-xs tw-text-gray-500">
                                        Candidature avant le {{ $jobOffer->deadline->format('d/m/Y') }}
                                    </span>
                                    <a href="{{ route('public.job-offers.show', $jobOffer->id) }}" 
                                       class="tw-bg-orange-400 hover:tw-bg-orange-500 tw-text-white tw-px-4 tw-py-2 tw-rounded-lg tw-text-sm tw-font-medium tw-transition tw-duration-200">
                                        Voir l'offre
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="tw-mt-12">
                    {{ $jobOffers->links() }}
                </div>
            @else
                {{-- État vide --}}
                <div class="tw-text-center tw-py-16">
                    <i class="fas fa-briefcase tw-text-gray-400 tw-text-6xl tw-mb-6"></i>
                    <h3 class="tw-text-2xl tw-font-semibold tw-text-gray-900 tw-mb-4">
                        Aucune offre disponible
                    </h3>
                    <p class="tw-text-gray-600 tw-mb-8 tw-max-w-md tw-mx-auto">
                        Il n'y a actuellement aucune offre d'emploi disponible. Revenez bientôt pour découvrir de nouvelles opportunités !
                    </p>
                    <a href="#" class="tw-bg-orange-400 hover:tw-bg-orange-500 tw-text-white tw-px-6 tw-py-3 tw-rounded-lg tw-font-medium tw-transition tw-duration-200">
                        Être alerté des nouvelles offres
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection