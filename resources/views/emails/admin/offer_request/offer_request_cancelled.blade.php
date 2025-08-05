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
                                        <img src="http://15.185.44.189/images/new_sayara.png">
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td style="background-color: #fff;padding:15px;text-align:center;">

                                    @if($offerRequest['type'] == 1)
                                    <h1>Offer Request Cancelled</h1>
                                @elseif($offerRequest['type'] == 2)
                                    <h1>On Road Price Request Cancelled</h1>
                                @elseif($offerRequest['type'] == 3)
                                    <h1>EMI Request Cancelled</h1>
                                @endif
                                </td>
                            </tr>

                            <tr style="background-color: #fff;text-align: -webkit-center;">
                                <td style="padding-bottom: 40px;">
                                    <table width="300" style="border-spacing: 0;background-color:#F9F9F9;padding:12px;">

                                        <td width="200" style="padding-left:20px;">
                                            @if ($offerRequest['type'] == 1)

                                                Hi <b>{{ $offerRequest->first_name }} {{ $offerRequest->last_name }},</b>

                                                We regret to inform you that your Offer Request has been cancelled.

                                                If you have any questions, please feel free to reach out.
                                            @elseif ($offerRequest['type'] == 2)
                                                Hi <b>{{ $offerRequest->first_name }} {{ $offerRequest->last_name }},</b>

                                                We regret to inform you that your On Road Price Request has been cancelled.

                                                If you have any questions, please feel free to reach out.
                                            @elseif ($offerRequest['type'] == 3)
                                                Hi <b>{{ $offerRequest->first_name }} {{ $offerRequest->last_name }},</b>

                                                We regret to inform you that your EMI Request has been cancelled.

                                                If you have any questions, please feel free to reach out.
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

