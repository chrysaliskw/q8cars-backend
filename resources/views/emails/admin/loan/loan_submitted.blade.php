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
                                        <img src="{{ asset('moltran-asset/images/q8_logo.svg') }}" >
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td style="background-color: #fff;padding:15px;text-align:center;">
                                    <h1>Loan Request Submitted</h1>
                                </td>
                            </tr>

                            <tr style="background-color: #fff;text-align: -webkit-center;">
                                <td style="padding-bottom: 40px;">
                                    <table width="300" style="border-spacing: 0;background-color:#F9F9F9;padding:12px;">

                                        <td width="200" style="padding-left:20px;">

                                             Hi <b>{{ $loan_request['first_name'] }} {{ $loan_request['last_name'] }}</b>,

                                             We are pleased to inform you that your loan request for {{ $bank_name }} has been <b>submitted</b>.

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

