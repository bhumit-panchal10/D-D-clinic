<ul class="nav nav-pills animation-nav nav-justified mb-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('patient_treatments.index', 'patient_treatments.create')) active @endif"
            href="{{ route('patient_treatments.index', $id) }}" role="tab">
            Patient Treatments
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('quotation.index', 'quotation.create')) active @endif" href="{{ route('quotation.index', $id) }}"
            role="tab">
            Quotation
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('patient_notes.index')) active @endif"
            href="{{ route('patient_notes.index', $id) }}" role="tab">
            Notes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('orders.index', 'orders.create')) active @endif" href="{{ route('orders.index', $id) }}"
            role="tab">
            Invoice
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('payments.index', 'payments.edit')) active @endif" href="{{ route('payments.index', $id) }}"
            role="tab">
            Payments
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('patientconcernform.index')) active @endif"
            href="{{ route('patientconcernform.index', $id) }}" role="tab">
            Consent Form
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('document.index')) active @endif" href="{{ route('document.index', $id) }}"
            role="tab">
            Documents
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('labworks.index')) active @endif" href="{{ route('labworks.index', $id) }}"
            role="tab">
            Lab work
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('prescriptions.index', 'prescriptions.create', 'prescriptions.edit')) active @endif"
            href="{{ route('prescriptions.index', $id) }}" role="tab">
            Prescription
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('patient_appointment.index', 'patient_appointment.create', 'patient_appointment.edit')) active @endif"
            href="{{ route('patient_appointment.index', $id) }}" role="tab">
            Appointments
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('pay_to_dr.index')) active @endif || @if (request()->routeIs('pay_to_dr.create')) active @endif || @if (request()->routeIs('pay_to_dr.edit')) active @endif"
            href="{{ route('pay_to_dr.index', $id) }}" role="tab">
            Pay To Dr
        </a>
    </li>
</ul>
