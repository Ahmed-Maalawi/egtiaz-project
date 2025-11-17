@component('mail::message')
    # 🎉 All Papers Completed Successfully!

    Dear **{{ $moderatorName }}**,

    We are pleased to inform you that all required papers for employee **{{ $employeeName }}** have been completed.

    ---

    ### Summary Information

    - **Employee Name:** {{ $employeeName }}
    - **Company:** {{ $companyName }}
    - **Total Stages Completed:** {{ $totalStages }}
    - **Completion Date:** {{ $completedAt }}

    ---

    ### Completed Stages

    @foreach($completedStages as $stage)
        - ✅ **{{ $stage->stage->name }}**
        Completed on: {{ $stage->completed_at->format('d M Y, h:i A') }}
    @endforeach

    ---

    If you have any questions, feel free to contact us.

    Thanks,
    **{{ config('app.name') }} Team**

    ---

    <small>This is an automated message. Please do not reply.</small>
@endcomponent
