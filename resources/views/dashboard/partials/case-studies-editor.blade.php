<div>
    <label class="field-label">Homepage Case Studies</label>
    <p class="field-help">Configure the four case study cards shown between the hero banner and products section. Each card renders a fixed Read more button using the link you provide.</p>

    <div class="case-study-editor-list">
        @foreach ($caseStudiesConfig ?? [] as $index => $caseStudy)
            @php
                $caseStudyImagePath = trim((string) ($caseStudy['image_path'] ?? ''));
                $caseStudyImageUrl = $caseStudyImagePath !== ''
                    ? route('media.show', ['path' => $caseStudyImagePath])
                    : null;
            @endphp

            <div class="case-study-editor-card">
                <h3 class="case-study-editor-title">Case Study {{ $index + 1 }}</h3>

                <div class="case-study-editor-grid">
                    <div>
                        <label class="field-label" for="case-study-label-{{ $index }}">Label</label>
                        <input class="field-input" id="case-study-label-{{ $index }}" name="case_studies[{{ $index }}][label]" type="text" value="{{ old("case_studies.$index.label", $caseStudy['label']) }}" maxlength="80" required>
                    </div>

                    <div>
                        <label class="field-label" for="case-study-title-{{ $index }}">Title</label>
                        <input class="field-input" id="case-study-title-{{ $index }}" name="case_studies[{{ $index }}][title]" type="text" value="{{ old("case_studies.$index.title", $caseStudy['title']) }}" maxlength="255" required>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label class="field-label" for="case-study-excerpt-{{ $index }}">Summary</label>
                        <textarea class="field-textarea" id="case-study-excerpt-{{ $index }}" name="case_studies[{{ $index }}][excerpt]" style="min-height:120px;">{{ old("case_studies.$index.excerpt", $caseStudy['excerpt']) }}</textarea>
                    </div>

                    <div>
                        <label class="field-label" for="case-study-href-{{ $index }}">Read More Link</label>
                        <input class="field-input" id="case-study-href-{{ $index }}" name="case_studies[{{ $index }}][href]" type="text" value="{{ old("case_studies.$index.href", $caseStudy['href']) }}" maxlength="255" required>
                    </div>

                    <div>
                        <label class="field-label" for="case-study-image-alt-{{ $index }}">Image Alt Text</label>
                        <input class="field-input" id="case-study-image-alt-{{ $index }}" name="case_studies[{{ $index }}][image_alt]" type="text" value="{{ old("case_studies.$index.image_alt", $caseStudy['image_alt']) }}" maxlength="255">
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label class="field-label" for="case-study-image-{{ $index }}">Card Image</label>
                        <input class="file-input" id="case-study-image-{{ $index }}" name="case_study_images[{{ $index }}]" type="file" accept=".jpg,.jpeg,.png,.webp">

                        @if ($caseStudyImageUrl)
                            <div class="hero-preview">
                                <img src="{{ $caseStudyImageUrl }}" alt="{{ $caseStudy['image_alt'] }}">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
