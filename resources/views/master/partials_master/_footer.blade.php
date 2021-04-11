<footer class="page-footer btn-mdb-color text-center text-md-left">
    <div class="container">
        <div class="row text-center text-md-left mt-4 pb-3">

            {{-- SOBRE RODAPÉ--}}
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                <h6 class="text-uppercase mb-4 font-weight-bold">Sobre a empresa</h6>
                <p>
                    &emsp; A <strong>Corporatix</strong> foi fundada em 2021 por Rafael Blum para atender as necessidades em
                    demandas tecnologicas de comunicação para pequenas e médias empresas.

                    &emsp; Com a integração da intranet corporativa Navegue na Intranet dentro de sua empresa.
                    Uma experiência imersiva e completa para os colaboradores da empresa,
                    unificando a comunicação na mesma ferramenta.

                </p>
            </div>

            <hr class="w-100 clearfix d-md-none">

            {{-- CATEGORIAS RODAPÉ--}}
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 mx-auto mt-3 text-center">
                <h6 class="text-uppercase font-weight-bold">Categorias</h6>

                {{--NOTÍCIAS --}}
                <p class="mb-1">
                    <a href="{{route('post.all')}}">
                        Notícias
                    </a>
                </p>

                @can('app.dashboard')
                {{--USUÁRIOS --}}
                <p class="mb-1">
                    <a href="{{route('user.index')}}">
                        Usuários
                    </a>
                </p>
                @endcan

                {{--CATEGORIAS --}}
                <p class="mb-1">
                    <a href="{{route('categoria.index')}}">
                        Categorias
                    </a>
                </p>

                {{--CARGO --}}
                <p class="mb-1">
                    <a href="{{route('cargo.index')}}">
                        Cargos
                    </a>
                </p>

                {{--UNIDADE --}}
                <p class="mb-1">
                    <a href="{{route('unidade.index')}}">
                        Unidades
                    </a>
                </p>

                {{--QUESTIONARIO --}}
                <p class="mb-1">
                    <a href="{{route('quest.do.finaliza')}}">
                        Questionarios
                    </a>
                </p>

                @can('app.dashboard')
                <p class="mb-1">
                    <a href="{{route('role.index')}}">
                        Permissões
                    </a>
                </p>
                @endcan
            </div>

            <hr class="w-100 clearfix d-md-none">

            {{-- CONTATO RODAPÉ--}}
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                <h6 class="text-uppercase mb-4 font-weight-bold">Contato</h6>
                <p>
                    <i class="fas fa-home mr-3"></i> Porto Alegre/RS, Brasil</p>
                <p>
                    <i class="fas fa-envelope mr-3"></i> suporte@corporatix.com.br</p>
                <p>
                    <i class="fas fa-phone mr-3"></i> + (51)33555555</p>
                <p>
                    <i class="fas fa-print mr-3"></i> + (51)33555555</p>
            </div>

        </div>

        {{-- DOMÍNIO E MIDIAS SOCIAIS RODAPÉ--}}
        <div class="row py-3 d-flex align-items-center">

            {{-- DOMÍNIO RODAPÉ--}}
            <div class="col-md-7 col-lg-8">
                <p class="text-center text-md-left text-white">
                    Copyright &copy; {{date('Y')}} <a href="{{route('home')}}" target="_blank"> corporatix.com.br </a>
                </p>
            </div>

            {{-- MIDIAS SOCIAIS RODAPÉ--}}
            <div class="col-md-5 col-lg-4 ml-lg-0">
                <div class="social-section text-center text-md-left">
                    <ul class="list-unstyled list-inline">
                        <li class="list-inline-item mx-0">
                            <a href="https://www.facebook.com/rafael.blum.3" class="btn-floating btn-sm rgba-white-slight mr-xl-4 waves-effect waves-light" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                        <li class="list-inline-item mx-0">
                            <a href="https://www.instagram.com/rafablum_" class="btn-floating btn-sm rgba-white-slight mr-xl-4 waves-effect waves-light" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                        <li class="list-inline-item mx-0">
                            <a href="https://www.linkedin.com/in/rafael-blum-237133114" class="btn-floating btn-sm rgba-white-slight mr-xl-4 waves-effect waves-light" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>