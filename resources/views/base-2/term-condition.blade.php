@extends('base-2.layout')

@section('content')
    <section class="my-5 py-5">
        <div class="container px-5">
            <div class="text-center mb-3">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid">
            </div>
            <div class="text-center mb-5">
                <h2 class="fw-bold">TERMS AND CONDITIONS</h2>
            </div>
            <ol>
                <li>All payments must be received before your session begins.</li>
                <li>Booking: All sessions must be booked in advance. Please
                    call our studio or use our online booking system to schedule
                    your session.</li>
                <li>Attendance: Please arrive on time for your session. If you
                    arrive late, the session will still end at the scheduled time, and
                    you will still be charged the full session rate.</li>
                <li>Attire: Please wear comfortable clothing that allows you to
                    move freely. We recommend wearing grip socks for hygiene
                    easons.</li>
                <li>Equipment: Our studio provides mats, blocks, straps, and
                    other necessary equipment. Please do not bring your own
                    equipment unless approved by our instructors.</li>
                <li>Health and Safety: Please inform your instructor of any
                    injuries or health conditions before starting your session. If you
                    feel any discomfort or pain during the session, inform your
                    instructor immediately.</li>
                <li>If class is less than 2 pax in 1 session, studio is able to
                    cancel the class 3 hours before conduct,</li>
                <li>Reschedule and Refunds:
                    <ul>
                        <li>All sessions purchased are non-refundable.</li>
                        <li>Rescheduling of a session must be done at least 24
                            hours before the scheduled session time.</li>
                        <li>Late cancellations, Reschedule or no-shows will result in a
                            penalty made less than 24 hours before the scheduled session
                            time will result in the forfeit of that session.</li>
                        <li>Exceptions to these rules will be made in the event of
                            sudden illness or emergency situations, and will be
                            handled on a case-by-case basis.</li>
                    </ul>
                </li>
            </ol>
        </div>
    </section>
@endsection
