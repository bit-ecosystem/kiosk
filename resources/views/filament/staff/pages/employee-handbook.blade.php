<x-filament-panels::page>
    <style>
        .pdf-container {
            position: relative;
            overflow: hidden;
        }

        .pdf-frame {
            position: fixed;
            top: 0;
            right: -75%;
            width: 75%;
            height: 100%;
            border: none;
            transition: right 0.5s ease-in-out;
            z-index: 1000;
            /* Ensure it appears above other content */
        }

        .pdf-container.active .pdf-frame {
            right: 0;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            /* Ensure it appears below the iframe but above other content */
            display: none;
        }

        .overlay.active {
            display: block;
        }
    </style>

    <div class="px-4 py-6 text-xs">
        @if (!empty($handbook['sections']))
            @foreach ($handbook['sections'] as $section)
                <h1 class="text-xl font-bold">{{ $section['title'] }}</h1>
                <div class="p-4">
                    @if (!empty($section['subsections']))
                        @foreach ($section['subsections'] as $subsection)
                            <div class="p-1">
                                <h2 class="text-lg font-semibold">{{ $subsection['title'] }}</h2>
                                <p class="indent-1">{{ $subsection['content'] }}</p>
                                @if (!empty($subsection['app']))
                                    <div class="pdf-container text-right">
                                        <a href="{{ $subsection['app'] }}" class="text-blue-500 pdf-link" target="_blank">Go To <span class="text-blue-500 underline">{{ basename($subsection['app']) }}</span></a>
                                    </div>
                                @endif
                                @if (!empty($subsection['pdfLink']))
                                    <div class="pdf-container text-right">
                                        <a href="{{ $subsection['pdfLink'] }}" class="text-blue-500 pdf-link" target="_blank">View policy: <span class="text-blue-500 underline">{{ basename($subsection['pdfLink']) }}</span></a>
                                        <iframe src="{{ $subsection['pdfLink'] }}" class="pdf-frame"></iframe>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        @else
            <p>No sections available.</p>
        @endif
    </div>

    <div class="overlay"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const appLinks = document.querySelectorAll('.app');
            const pdfLinks = document.querySelectorAll('.pdf-link');
            const overlay = document.querySelector('.overlay');

            pdfLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const container = this.closest('.pdf-container');
                    container.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
            });

            overlay.addEventListener('click', function() {
                const activeContainer = document.querySelector('.pdf-container.active');
                if (activeContainer) {
                    activeContainer.classList.remove('active');
                }
                overlay.classList.remove('active');
            });
        });
    </script>
</x-filament-panels::page>
