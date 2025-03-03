@extends('app.page')
@section('title', '- Chat')

@include('js.chat')

@section('content')
<div class="hero hero-inner">
    <div class="container">
    <div class="row align-items-center">
        <div class="col-lg-6 mx-auto text-center">
        <div class="intro-wrap">
            <h1 class="mb-0">Chat</h1>
        </div>
        </div>
    </div>
    </div>
</div>

<div class="untree_co-section">
    <main class="content">
        <div class="container p-0">

            <h1 class="h3 mb-3">Chat Grup Destinasi</h1>

            <div class="card">
                <div class="row g-0">
                    <div class="col-12 col-lg-5 col-xl-3 border-right">

                        <div class="px-4 d-none d-md-block">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <input type="text" id="search-chat" class="form-control my-3" placeholder="Search...">
                                </div>
                            </div>
                        </div>

                        <section id="chat-list">

                            <h5>Memuat...</h5>

                        </section>

                        <hr class="d-block d-lg-none mt-1 mb-0">
                    </div>
                    <div class="col-12 col-lg-7 col-xl-9">
                        <div class="py-2 px-4 border-bottom d-none d-lg-block">
                            <div class="d-flex align-items-center py-1">
                                <div class="position-relative">
                                    <img src="{{ url('assets/images/icon.png') }}" class="rounded-circle mr-1" alt="Img Channel" width="40" height="40" id="foto-channel">
                                </div>
                                <div class="flex-grow-1 ps-3">
                                    <strong id="nama-channel">Nama Channel</strong>
                                </div>
                            </div>
                        </div>

                        <div class="position-relative">
                            <div class="chat-messages p-4">

                            </div>
                        </div>

                        <div class="flex-grow-0 py-3 px-4 border-top send-chat d-none">
                            <div class="input-group">
                                <input type="text" class="form-control" id="message-text" placeholder="Type your message">
                                <button class="btn btn-primary send-message">Send</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
