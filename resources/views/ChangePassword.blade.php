<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Passcode</title>
</head>
<body style="margin: 0; padding: 0;">

<div class="text-center" style="margin-top: 50px;">

    <table class="wrapper" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <tr>

            <td align="center">
                <h1>Please get your QR code to submit reset password form in the follow link!</h1>
                <h3>Laravel QR Code Example</h3>
                <qr></qr>
                <a href="{{'http://localhost:3000/#/changePassword/'.$details['address']}}">Link</a>
            </td>
        </tr>
        <tr>
            <td align="center">
                <img
                    src="{!!$message->embedData(QrCode::format('png')->size(400)->generate($details['qrCode']), 'QrCode.png', 'image/png')!!}">
            </td>
        </tr>
        <tr>

            <td align="center">
                <h1>Thank you!!!</h1>
                <qr></qr>
                <h3>Huy</h3>
            </td>
        </tr>
    </table>

</div>


</body>
</html>
