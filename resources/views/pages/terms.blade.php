<!DOCTYPE html>
<html lang="hu">
<head>
        <title>Meme.SCH - Felhasználói Feltételek</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    </head>
    <body class="container">
        <div class="container">
            <h1 class="mt-3 text-center mb-0">Olvass el!</h1>
            <h2 class="text-center text-muted mt-0">(légyszi)</h2>

            <div class="d-flex justify-content-center mt-5">
                <div class="col-md-6">
                    <div class="card">
                        <div class="accordion accordion-flush" id="terms-tldr">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="terms-tldr-button">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#terms-tldr-content">tl;dr</button>
                                </h2>
                                <div id="terms-tldr-content" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <form action="{{ route('terms.accept') }}" method="POST">
                                            @csrf
                                            <p @if($user->hasAcceptedTerms()) class="mb-0" @endif><b>Ne legyél faszszopó</b></p>
                                            @if (!$user->hasAcceptedTerms())
                                                <div class="form-check form-switch mb-3">
                                                    <input type="checkbox" class="form-check-input" id="accept-tldr" name="accept-tldr" required>
                                                    <label for="accept-tldr" class="form-check-label">Hagyjmár elfogadom baszod</label>
                                                </div>
                                                <input type="submit" class="btn btn-primary btn-sm" value="Zsa">
                                                <a class="btn btn-secondary btn-sm" href="{{ route('auth.logout') }}">Esélytelen</a>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex mt-3 justify-content-center">
                <div class="col-md-6">
                    <div class="card p-3">
                        <h4 class="card-title">Csak viccek</h4>
                        <p>Az oldalon található kontent csak a humor célját szolglája, ha valami nem tetszik akkor reportold és egyik adminunk majd eldönti, hogy tényleg para-e ami ki lett rakva.</p>
                        <p>Ha végül úgy döntünk, hogy nem para és nem töröljük, akkor <b>légyszi</b> ne foglalkozz vele többet, mert <u>mi sem fogunk.</u></p>
                        <p class="mb-0">Alapvetően úgy gondoljuk, hogy a cenzúra egy <b>rossz</b> dolog, bármit is gondoljon az aktuális miniszterelnök Úr.</p>
                    </div>
                </div>
            </div>

            <div class="d-flex mt-3 justify-content-center">
                <div class="col-md-6">
                    <div class="card p-3">
                        <h4 class="card-title mb-0">Posztolási szabályok</h4>
                        <h6 class="text-muted mt-0"><small>Avagy "Mikor törlünk posztot?"</small></h6>
                        <p class="mb-1">Alapvetően a cenzúra rósz, ezért a körök saját oldalait nem áll módunkban cenzúrázni, az az aktuális körvez feladata ha úgy érzi.</p>
                        <p class="mb-1"><b>VISZONT</b></p>
                        <p>a nagy közös kolis rész más kérdés, ott a következő szabályok élnek:</p>
                        <ol class="mb-0">
                            <li>Gyermekpornográfia kurvára off <small>(reméltem ez alap, de mint kiderült: nem)</small></li>
                            <li>Gyűlöletkeltő posztokat hanyagoljuk</li>
                            <li>Egy ember buzizását mellőzzük <small>(ne személyeskedjünk pls)</small></li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <div class="col-md-6">
                    <div class="card p-3">
                        <h4 class="card-title">Fejlesztés alatt</h4>
                        <p>Kiemelném, hogy az oldalt <b>egyedül</b> fejlesztem <b data-bs-toggle="tooltip" data-bs-placement="right" title="amiből nem sok van mostanság">szabadidőmben</b>.
                            Így ha helyeken szar, ezért bocsánatot szeretnék kérni :D Nem minden bugról tudok, és nincs minden kész.
                        </p>
                        <p>A frontend <span
                                style="
                                text-decoration:underline;
                                text-decoration-style:dotted;
                                cursor:help;
                            "
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="(bootstrap + jQuery)"
                            >szar</span>, mert ehhez értek.</p>
                        <p class="mb-0"><b>VISZONT</b>, ha</p>
                        <ul class="mt-0 mb-0">
                            <li>találsz egy bugot, akkor jelezd: <a href="mailto:support@mail.memesch.net">support@mail.memesch.net</a></li>
                            <li>ötleted van új feature-höz, akkor jelezd: <a href="mailto:support@mail.memesch.net">support@mail.memesch.net</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="d-flex mt-3 justify-content-center">
                <div class="col-md-6">
                    <div class="card p-3">
                        <p class="mb-0">Röviden ennyi, úgyis lesz még valami ami eszembejut, az majd kikerül ide és mindenki elolvashatja újból ezt a gyönyört.</p>
                    </div>
                </div>
            </div>
            <div class="d-flex mt-3 justify-content-center mb-3">
                <div class="col-md-6">
                    <div class="card">
                        @if (!$user->hasAcceptedTerms())
                            <form action="{{ route('terms.accept') }}" method="POST">
                                <div class="card-body">
                                    @csrf
                                    <div class="form-check form-switch mb-0">
                                        <input type="checkbox" class="form-check-input" name="accept" id="accept" required>
                                        <label for="accept" class="form-check-label">Elfogadom a fent leírt szabályokat, és megpróbálok nem csicska lenni.</label>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Tovább &raquo;</button>
                                    <a href="{{ route('auth.logout') }}" class="btn btn-secondary">Inkább nem</a>
                                </div>
                            </form>
                        @else
                            <a href="#" onclick="history.back()" class="btn btn-secondary">Vissza</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
