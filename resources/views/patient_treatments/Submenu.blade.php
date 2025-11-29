<ul class="nav nav-pills animation-nav nav-justified mb-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('ReasonForVisitToday.index', 'ReasonForVisitToday.create')) active @endif"
            href="{{ route('ReasonForVisitToday.index', $id) }}" role="tab">
            Reason For Visit Today
        </a>
    </li>

    {{-- <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('ExtraoralExamination.index', 'quotation.create')) active @endif"
            href="{{ route('ExtraoralExamination.index', $id) }}" role="tab">
            Extraoral Examination
        </a>
    </li> --}}

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('IntraoralExamination.index')) active @endif"
            href="{{ route('IntraoralExamination.index', $id) }}" role="tab">
            Intraoral Examination
        </a>
    </li>

</ul>
