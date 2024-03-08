<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <body>
        <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
            <div class="visible-print text-center">
                
                {!! QrCode::size(500)->generate($qr_text); !!}
                <p>Scan me to return to the original page.</p>
            </div>
        </div>
    </body>
</html>
