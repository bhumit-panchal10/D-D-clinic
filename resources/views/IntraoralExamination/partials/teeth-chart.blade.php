{{-- resources/views/intraoral/partials/teeth-chart.blade.php --}}
<div class="teeth-chart-container">
    <!-- Adult Teeth -->
    <div class="adult-teeth">
        <!-- Upper Jaw -->
        <div class="mb-3">
            <div class="text-center mb-2 small text-muted"><strong>Upper Jaw</strong></div>
            <div class="d-flex justify-content-center mb-2">
                <!-- Upper Right -->
                <div class="d-flex flex-wrap" style="width: 180px;">
                    @foreach ([18, 17, 16, 15, 14, 13, 12, 11] as $tooth)
                        @php
                            $isSelected = in_array($tooth, $selectedTeeth ?? []);
                        @endphp
                        <div class="teeth_wrapper teeth-selectable" data-section="{{ $section }}"
                            data-tooth="{{ $tooth }}" title="Tooth {{ $tooth }}">
                            <img src="{{ $isSelected ? asset('assets/images/TeethGreen/' . $tooth . '.png') : asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                alt="{{ $tooth }}"
                                data-yellow="{{ asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                data-green="{{ asset('assets/images/TeethGreen/' . $tooth . '.png') }}"
                                class="{{ $isSelected ? 'selected tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Upper Left -->
                <div class="d-flex flex-wrap" style="width: 180px;">
                    @foreach ([21, 22, 23, 24, 25, 26, 27, 28] as $tooth)
                        @php
                            $isSelected = in_array($tooth, $selectedTeeth ?? []);
                        @endphp
                        <div class="teeth_wrapper teeth-selectable" data-section="{{ $section }}"
                            data-tooth="{{ $tooth }}" title="Tooth {{ $tooth }}">
                            <img src="{{ $isSelected ? asset('assets/images/TeethGreen/' . $tooth . '.png') : asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                alt="{{ $tooth }}"
                                data-yellow="{{ asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                data-green="{{ asset('assets/images/TeethGreen/' . $tooth . '.png') }}"
                                class="{{ $isSelected ? 'selected tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Lower Jaw -->
        <div>
            <div class="text-center mb-2 small text-muted"><strong>Lower Jaw</strong></div>
            <div class="d-flex justify-content-center">
                <!-- Lower Right -->
                <div class="d-flex flex-wrap" style="width: 180px;">
                    @foreach ([48, 47, 46, 45, 44, 43, 42, 41] as $tooth)
                        @php
                            $isSelected = in_array($tooth, $selectedTeeth ?? []);
                        @endphp
                        <div class="teeth_wrapper teeth-selectable" data-section="{{ $section }}"
                            data-tooth="{{ $tooth }}" title="Tooth {{ $tooth }}">
                            <img src="{{ $isSelected ? asset('assets/images/TeethGreen/' . $tooth . '.png') : asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                alt="{{ $tooth }}"
                                data-yellow="{{ asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                data-green="{{ asset('assets/images/TeethGreen/' . $tooth . '.png') }}"
                                class="{{ $isSelected ? 'selected tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Lower Left -->
                <div class="d-flex flex-wrap" style="width: 180px;">
                    @foreach ([31, 32, 33, 34, 35, 36, 37, 38] as $tooth)
                        @php
                            $isSelected = in_array($tooth, $selectedTeeth ?? []);
                        @endphp
                        <div class="teeth_wrapper teeth-selectable" data-section="{{ $section }}"
                            data-tooth="{{ $tooth }}" title="Tooth {{ $tooth }}">
                            <img src="{{ $isSelected ? asset('assets/images/TeethGreen/' . $tooth . '.png') : asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                alt="{{ $tooth }}"
                                data-yellow="{{ asset('assets/images/TeethYellow/' . $tooth . '.png') }}"
                                data-green="{{ asset('assets/images/TeethGreen/' . $tooth . '.png') }}"
                                class="{{ $isSelected ? 'selected tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Child Teeth (Hidden by default) -->
    <div class="child-teeth">
        <!-- Upper Jaw -->
        <div class="mb-3">
            <div class="text-center mb-2 small text-muted"><strong>Upper Jaw (Primary)</strong></div>
            <div class="d-flex justify-content-center mb-2">
                <!-- Upper Left Primary -->
                <div class="d-flex flex-wrap" style="width: 180px;">
                    @php
                        $upperLeftMapping = [
                            '55' => '1E',
                            '54' => '1D',
                            '53' => '1C',
                            '52' => '1B',
                            '51' => '1A',
                        ];
                        $selectedTeeth = $selectedTeeth ?? [];
                    @endphp

                    @foreach (['55', '54', '53', '52', '51'] as $tooth)
                        @php
                            $isSelected = in_array($tooth, $selectedTeeth);
                            $imageCode = $upperLeftMapping[$tooth];
                        @endphp
                        <div class="teeth_wrapper teeth-selectable" data-section="{{ $section }}"
                            data-tooth="{{ $tooth }}" title="Tooth {{ $tooth }}">
                            <img src="{{ $isSelected ? asset('assets/images/ChildGreenTeeth/' . $imageCode . '.png') : asset('assets/images/ChildYellowTeeth/' . $imageCode . '.png') }}"
                                alt="{{ $tooth }}"
                                data-yellow="{{ asset('assets/images/ChildYellowTeeth/' . $imageCode . '.png') }}"
                                data-green="{{ asset('assets/images/ChildGreenTeeth/' . $imageCode . '.png') }}"
                                class="{{ $isSelected ? 'selected tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Upper Right Primary -->
                <div class="d-flex flex-wrap" style="width: 180px;">
                    @php
                        $upperRightMapping = [
                            '61' => '2A',
                            '62' => '2B',
                            '63' => '2C',
                            '64' => '2D',
                            '65' => '2E',
                        ];
                        $selectedTeeth = $selectedTeeth ?? [];
                    @endphp

                    @foreach (['61', '62', '63', '64', '65'] as $tooth)
                        @php
                            $isSelected = in_array($tooth, $selectedTeeth);
                            $imageCode = $upperRightMapping[$tooth];
                        @endphp
                        <div class="teeth_wrapper teeth-selectable" data-section="{{ $section }}"
                            data-tooth="{{ $tooth }}" title="Tooth {{ $tooth }}">
                            <img src="{{ $isSelected ? asset('assets/images/ChildGreenTeeth/' . $imageCode . '.png') : asset('assets/images/ChildYellowTeeth/' . $imageCode . '.png') }}"
                                alt="{{ $tooth }}"
                                data-yellow="{{ asset('assets/images/ChildYellowTeeth/' . $imageCode . '.png') }}"
                                data-green="{{ asset('assets/images/ChildGreenTeeth/' . $imageCode . '.png') }}"
                                class="{{ $isSelected ? 'selected tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Lower Jaw -->
        <div>
            <div class="text-center mb-2 small text-muted"><strong>Lower Jaw (Primary)</strong></div>
            <div class="d-flex justify-content-center">
                <!-- Lower Left Primary -->
                <div class="d-flex flex-wrap" style="width: 180px;">
                    @php
                        $lowerLeftMapping = [
                            '85' => '3E',
                            '84' => '3D',
                            '83' => '3C',
                            '82' => '3B',
                            '81' => '3A',
                        ];
                        $selectedTeeth = $selectedTeeth ?? [];
                    @endphp

                    @foreach (['85', '84', '83', '82', '81'] as $tooth)
                        @php
                            $isSelected = in_array($tooth, $selectedTeeth);
                            $imageCode = $lowerLeftMapping[$tooth];
                        @endphp
                        <div class="teeth_wrapper teeth-selectable" data-section="{{ $section }}"
                            data-tooth="{{ $tooth }}" title="Tooth {{ $tooth }}">
                            <img src="{{ $isSelected ? asset('assets/images/ChildGreenTeeth/' . $imageCode . '.png') : asset('assets/images/ChildYellowTeeth/' . $imageCode . '.png') }}"
                                alt="{{ $tooth }}"
                                data-yellow="{{ asset('assets/images/ChildYellowTeeth/' . $imageCode . '.png') }}"
                                data-green="{{ asset('assets/images/ChildGreenTeeth/' . $imageCode . '.png') }}"
                                class="{{ $isSelected ? 'selected tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Lower Right Primary -->
                <div class="d-flex flex-wrap" style="width: 180px;">
                    @php
                        $lowerRightMapping = [
                            '71' => '4A',
                            '72' => '4B',
                            '73' => '4C',
                            '74' => '4D',
                            '75' => '4E',
                        ];
                        $selectedTeeth = $selectedTeeth ?? [];
                    @endphp

                    @foreach (['71', '72', '73', '74', '75'] as $tooth)
                        @php
                            $isSelected = in_array($tooth, $selectedTeeth);
                            $imageCode = $lowerRightMapping[$tooth];
                        @endphp
                        <div class="teeth_wrapper teeth-selectable" data-section="{{ $section }}"
                            data-tooth="{{ $tooth }}" title="Tooth {{ $tooth }}">
                            <img src="{{ $isSelected ? asset('assets/images/ChildGreenTeeth/' . $imageCode . '.png') : asset('assets/images/ChildYellowTeeth/' . $imageCode . '.png') }}"
                                alt="{{ $tooth }}"
                                data-yellow="{{ asset('assets/images/ChildYellowTeeth/' . $imageCode . '.png') }}"
                                data-green="{{ asset('assets/images/ChildGreenTeeth/' . $imageCode . '.png') }}"
                                class="{{ $isSelected ? 'selected tooth-selected-' . $section : '' }}">
                            <div class="tooth-text">{{ $tooth }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
