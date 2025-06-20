{{-- resources/views/public/job-offers/show.blade.php --}}
@extends('layouts.public')

@section('content')
<div class="tw-min-h-screen tw-bg-gray-50 tw-py-8">
    <div class="tw-max-w-4xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
        {{-- Navigation --}}
        <div class="tw-mb-8">
            <a href="{{ route('public.job-offers.index') }}" 
               class="tw-inline-flex tw-items-center tw-text-orange-600 hover:tw-text-orange-800 tw-transition tw-duration-200">
                <i class="fas fa-arrow-left tw-mr-2"></i>
                Retour aux offres
            </a>
        </div>

        {{-- En-tête de l'offre --}}
        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-overflow-hidden tw-mb-8">
            <div class="tw-bg-gradient-to-r tw-from-orange-400 tw-to-orange-600 tw-px-6 tw-py-8 tw-text-white">
                <div class="tw-flex tw-items-start tw-justify-between">
                    <div class="tw-flex-1">
                        <h1 class="tw-text-3xl tw-font-bold tw-mb-2">{{ $jobOffer->title }}</h1>
                        <div class="tw-flex tw-items-center tw-space-x-4 tw-text-orange-100">
                            <span class="tw-flex tw-items-center">
                                <i class="fas fa-building tw-mr-2"></i>
                                {{ $jobOffer->department }}
                            </span>
                            <span class="tw-flex tw-items-center">
                                <i class="fas fa-map-marker-alt tw-mr-2"></i>
                                {{ $jobOffer->location }}
                            </span>
                            <span class="tw-flex tw-items-center">
                                <i class="fas fa-calendar tw-mr-2"></i>
                                {{ $jobOffer->type }}
                            </span>
                        </div>
                    </div>
                    <div class="tw-text-right">
                        @if($jobOffer->salary_range)
                            <p class="tw-text-xl tw-font-semibold tw-mb-1">{{ $jobOffer->salary_range }}</p>
                        @endif
                        <p class="tw-text-orange-100 tw-text-sm">
                            {{ $jobOffer->positions_available }} poste(s) disponible(s)
                        </p>
                    </div>
                </div>
            </div>

            <div class="tw-px-6 tw-py-4 tw-bg-orange-50 tw-border-b tw-border-orange-200">
                <div class="tw-flex tw-items-center tw-justify-between">
                    <p class="tw-text-sm tw-text-orange-800">
                        <i class="fas fa-clock tw-mr-2"></i>
                        Candidatures ouvertes jusqu'au {{ $jobOffer->deadline->format('d/m/Y') }}
                    </p>
                    <a href="{{ route('public.job-offers.apply-form', $jobOffer->id) }}" class="tw-bg-orange-500 hover:tw-bg-orange-600 tw-text-white tw-px-6 tw-py-2 tw-rounded-lg tw-font-medium tw-transition tw-duration-200">
                        <i class="fas fa-paper-plane tw-mr-2"></i>
                        Postuler maintenant
                    </a>
                </div>
            </div>
        </div>

        <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-8">
            {{-- Contenu principal --}}
            <div class="lg:tw-col-span-2 tw-space-y-8">
                {{-- Description du poste --}}
                <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                        <h2 class="tw-text-xl tw-font-semibold tw-text-gray-900">Description du Poste</h2>
                    </div>
                    <div class="tw-px-6 tw-py-6">
                        <div class="tw-prose tw-prose-gray tw-max-w-none">
                            <div class="tw-whitespace-pre-line tw-text-gray-700">{{ $jobOffer->description }}</div>
                        </div>
                    </div>
                </div>

                {{-- Exigences --}}
                @if($jobOffer->requirements)
                    <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-overflow-hidden">
                        <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                            <h2 class="tw-text-xl tw-font-semibold tw-text-gray-900">Exigences et Compétences</h2>
                        </div>
                        <div class="tw-px-6 tw-py-6">
                            <div class="tw-prose tw-prose-gray tw-max-w-none">
                                <div class="tw-whitespace-pre-line tw-text-gray-700">{{ $jobOffer->requirements }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Avantages --}}
                @if($jobOffer->benefits)
                    <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-overflow-hidden">
                        <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                            <h2 class="tw-text-xl tw-font-semibold tw-text-gray-900">Avantages</h2>
                        </div>
                        <div class="tw-px-6 tw-py-6">
                            <div class="tw-prose tw-prose-gray tw-max-w-none">
                                <div class="tw-whitespace-pre-line tw-text-gray-700">{{ $jobOffer->benefits }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="tw-space-y-6">
                {{-- Candidature --}}
                <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Postuler</h3>
                    </div>
                    <div class="tw-px-6 tw-py-6">
                        <a href="{{ route('public.job-offers.apply-form', $jobOffer->id) }}" class="tw-w-full tw-bg-orange-500 hover:tw-bg-orange-600 tw-text-white tw-px-6 tw-py-3 tw-rounded-lg tw-font-medium tw-transition tw-duration-200 tw-mb-4">
                            <i class="fas fa-paper-plane tw-mr-2"></i>
                            Postuler maintenant
                        </a>
                        <p class="tw-text-xs tw-text-gray-500 tw-text-center mt-4">
                            Votre candidature sera traitée dans les plus brefs délais
                        </p>
                    </div>
                </div>

                {{-- Informations sur l'offre --}}
                <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Informations</h3>
                    </div>
                    <div class="tw-px-6 tw-py-6">
                        <div class="tw-space-y-4">
                            <div>
                                <span class="tw-text-sm tw-font-medium tw-text-gray-500">Type de contrat</span>
                                <p class="tw-text-sm tw-text-gray-900 tw-mt-1">{{ $jobOffer->type }}</p>
                            </div>
                            
                            <div>
                                <span class="tw-text-sm tw-font-medium tw-text-gray-500">Localisation</span>
                                <p class="tw-text-sm tw-text-gray-900 tw-mt-1">{{ $jobOffer->location }}</p>
                            </div>
                            
                            <div>
                                <span class="tw-text-sm tw-font-medium tw-text-gray-500">Département</span>
                                <p class="tw-text-sm tw-text-gray-900 tw-mt-1">{{ $jobOffer->department }}</p>
                            </div>

                            @if($jobOffer->salary_range)
                                <div>
                                    <span class="tw-text-sm tw-font-medium tw-text-gray-500">Rémunération</span>
                                    <p class="tw-text-sm tw-text-gray-900 tw-mt-1">{{ $jobOffer->salary_range }}</p>
                                </div>
                            @endif
                            
                            <div>
                                <span class="tw-text-sm tw-font-medium tw-text-gray-500">Date limite</span>
                                <p class="tw-text-sm tw-text-gray-900 tw-mt-1">{{ $jobOffer->deadline->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection