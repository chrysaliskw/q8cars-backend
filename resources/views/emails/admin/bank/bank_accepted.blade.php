{{-- @component('mail::message')
Your Bank Suggestion has been Accepted!

Dear {{ $suggested_bank->first_name }} {{ $suggested_bank->last_name }},

We are pleased to inform you that your bank suggestion has been accepted.

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
                                    <h1>Bank Suggestion Request Accepted</h1>
                                </td>
                            </tr>

                            <tr style="background-color: #fff;text-align: -webkit-center;">
                                <td style="padding-bottom: 40px;">
                                    <table width="300" style="border-spacing: 0;background-color:#F9F9F9;padding:12px;">

                                        <td width="200" style="padding-left:20px;">

                                             Hi <b>{{ $suggested_bank->first_name }} {{ $suggested_bank->last_name }},</b>

                                             We are pleased to inform you that your bank suggestion has been accepted.

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


