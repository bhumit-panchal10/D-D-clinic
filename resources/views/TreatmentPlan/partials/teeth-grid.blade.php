{{-- resources/views/intraoral/partials/teeth-grid.blade.php --}}
<div class="teeth-grid" data-section="{{ $section }}">
    <div class="row">
        <!-- Upper Right -->
        <div class="col-6 border-end">
            <h6 class="text-center mb-3">{{ $title1 }}</h6>
            <div class="row d-flex justify-content-center">
                @foreach ([18, 17, 16, 15, 14, 13, 12, 11] as $tooth)
                    <div class="col-3 px-1 mb-2">
                        <div class="teeth_wrapper text-center" data-section="{{ $section }}">
                            <img src="{{ asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                alt="{{ $tooth }}" data-tooth="{{ $tooth }}"
                                class="{{ in_array($tooth, $selectedTeeth) ? 'tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Upper Left -->
        <div class="col-6">
            <h6 class="text-center mb-3">{{ $title2 }}</h6>
            <div class="row d-flex justify-content-center">
                @foreach ([21, 22, 23, 24, 25, 26, 27, 28] as $tooth)
                    <div class="col-3 px-1 mb-2">
                        <div class="teeth_wrapper text-center" data-section="{{ $section }}">
                            <img src="{{ asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                alt="{{ $tooth }}" data-tooth="{{ $tooth }}"
                                class="{{ in_array($tooth, $selectedTeeth) ? 'tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <hr class="my-3">

    <div class="row">
        <!-- Lower Right -->
        <div class="col-6 border-end">
            <h6 class="text-center mb-3">{{ $title3 }}</h6>
            <div class="row d-flex justify-content-center">
                @foreach ([48, 47, 46, 45, 44, 43, 42, 41] as $tooth)
                    <div class="col-3 px-1 mb-2">
                        <div class="teeth_wrapper text-center" data-section="{{ $section }}">
                            <img src="{{ asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                alt="{{ $tooth }}" data-tooth="{{ $tooth }}"
                                class="{{ in_array($tooth, $selectedTeeth) ? 'tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Lower Left -->
        <div class="col-6">
            <h6 class="text-center mb-3">{{ $title4 }}</h6>
            <div class="row d-flex justify-content-center">
                @foreach ([31, 32, 33, 34, 35, 36, 37, 38] as $tooth)
                    <div class="col-3 px-1 mb-2">
                        <div class="teeth_wrapper text-center" data-section="{{ $section }}">
                            <img src="{{ asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                alt="{{ $tooth }}" data-tooth="{{ $tooth }}"
                                class="{{ in_array($tooth, $selectedTeeth) ? 'tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
