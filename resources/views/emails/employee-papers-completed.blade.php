@component('mail::message')
    # 🎉 All Papers Completed Successfully!

    Dear **{{ $moderatorName }}**,

    We are pleased to inform you that all required papers and documents for your employee **{{ $employeeName }}** have been successfully processed and completed.

    ---

    ### Summary Information

    **Employee Name:** {{ $employeeName }}
    **Company:** {{ $companyName }}
    **Total Stages Completed:** {{ $totalStages }}
    **Completion Date:** {{ $completedAt }}

    ---

    ### Completed Stages

    @foreach($completedStages as $stage)
        ✅ **{{ $stage->stage->name }}**
        &nbsp;&nbsp;&nbsp;&nbsp;Completed on: {{ $stage->completed_at->format('d M Y, h:i A') }}

    @endforeach

    ---

    If you have any questions or need additional information, please don't hesitate to contact us.

    Thank you for choosing our services!

    Best regards,
    **{{ config('app.name') }} Team**

    ---

    <small style="color: #999;">This is an automated notification sent to company moderators. Please do not reply to this email.</small>
@endcomponent
