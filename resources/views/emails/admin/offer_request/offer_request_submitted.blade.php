{{-- @component('mail::message')
Your Loan Request has been Submitted!

Dear {{ $loan_request->first_name }} {{ $loan_request->last_name }},

We are pleased to inform you that your loan request for {{ $loan_request->bank_name }} has been submitted.

Thank you for choosing us!

@endcomponent --}}

<!DOCTYPE html>
<html>

<head>
    <title>Q8 Cars</title>
</head>

<body>
    <table width="500" height="100" align="center" style="margin-top:20px;">
        <tbody>
            <tr>
                <td width="500" align="center">
                    <table width="500" style="border: 1px solid #E9EAEC; border-spacing: 0;">
                        <tbody>
                            <tr>
                                <td style="background-color: #F9F9F9;padding:15px;">

                                    <a href="">
                                        <img src="http://15.185.44.189/images/kuwait-logo-dark.e252462e.svg">
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td style="background-color: #fff;padding:15px;text-align:center;">
                                    @if($offerRequest['type'] == 1)
                                        <h1>Offer Request Submitted</h1>
                                    @elseif($offerRequest['type'] == 2)
                                        <h1>On Road Price Request Submitted</h1>
                                    @elseif($offerRequest['type'] == 3)
                                        <h1>EMI Request Submitted</h1>
                                    @endif


                                </td>
                            </tr>

                            <tr style="background-color: #fff;text-align: -webkit-center;">
                                <td style="padding-bottom: 40px;">
                                    <table width="300" style="border-spacing: 0;background-color:#F9F9F9;padding:12px;">

                                        <td width="200" style="padding-left:20px;">

                                             Hi <b>{{ $offerRequest['full_name'] }}</b>,

                                             @if($offerRequest['type'] == 1)
                                                    @if(isset($details['car']) && isset($details['car_version']))
                                                        We are pleased to inform you that your offer request for {{ $details['car'] }} model, {{ $details['car_version'] }} variant has been <b>submitted</b>.
                                                    @elseif(isset($details['car']) && $details['car'] != 'Unknown Car')
                                                        We are pleased to inform you that your offer request for {{ $details['car'] }} model has been <b>submitted</b>.
                                                    @elseif(isset($details['car_version']) && $details['car_version'] != 'Unknown Car Version')
                                                        We are pleased to inform you that your offer request for {{ $details['car_version'] }} variant has been <b>submitted</b>.
                                                    @else
                                                        We are pleased to inform you that your offer request has been <b>submitted</b>.
                                                    @endif
                                            @elseif($offerRequest['type'] == 2)
                                                    @if(isset($details['car']) && isset($details['car_version']))
                                                        We are pleased to inform you that your on road price request for {{ $details['car'] }} model, {{ $details['car_version'] }} variant has been <b>submitted</b>.
                                                    @elseif(isset($details['car']) && $details['car'] != 'Unknown Car')
                                                        We are pleased to inform you that your on road price request for {{ $details['car'] }} model has been <b>submitted</b>.
                                                    @elseif(isset($details['car_version']) && $details['car_version'] != 'Unknown Car Version')
                                                        We are pleased to inform you that your on road price request for {{ $details['car_version'] }} variant has been <b>submitted</b>.
                                                    @else
                                                        We are pleased to inform you that your on road price request has been <b>submitted</b>.
                                                    @endif
                                            @elseif($offerRequest['type'] == 3)
                                                    @if(isset($details['car']) && isset($details['car_version']))
                                                        We are pleased to inform you that your EMI request for {{ $details['car'] }} model, {{ $details['car_version'] }} variant has been <b>submitted</b>.
                                                    @elseif(isset($details['car']) && $details['car'] != 'Unknown Car')
                                                        We are pleased to inform you that your EMI request for {{ $details['car'] }} model has been <b>submitted</b>.
                                                    @elseif(isset($details['car_version']) && $details['car_version'] != 'Unknown Car Version')
                                                        We are pleased to inform you that your EMI request for {{ $details['car_version'] }} variant has been <b>submitted</b>.
                                                    @else
                                                        We are pleased to inform you that your EMI request has been <b>submitted</b>.
                                                    @endif
                                            @endif



                                        </td>
                                    </table>
                                </td>
                            </tr>

                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" style="border-spacing: 0;padding-bottom: 50px;">
                                        <tbody>
                                            <tr>
                                                <td width="100%">
                                                        <p style="font-family:sans-serif; font-size: 16px; color: #192847;text-align:center;line-height:30px;">Thanks,<br>
                                                            {{ config('app.name') }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>

