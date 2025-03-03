@push('script')
<script>
    $(document).ready(function() {
        var counting_chat = 0
        var get_interval
        function get_list_chat(search = '') {
            param = {}
            if (search != '') {
                search = "?search="+search
            }
            callApi("GET", "{{ route('api.chat.seller') }}"+search, param, function (req) {
                list = '';
                $.each(req.data, function (index, val) {
                    list += `
                    <button class="list-group-item list-group-item-action border-0 mb-3 p-2 btn-chat-list" data-nama="${val.nama_channel}" data-id="${val.id}" data-foto="${val.foto}">
                        <div class="d-flex align-items-start">
                            <img src="{{url('')}}${val.foto}" class="rounded-circle mr-1" alt="${val.nama_channel}" width="40" height="40">
                            <div class="flex-grow-1 ms-3">
                                ${val.nama_channel}
                                <div class="small ${val.status=='opened'?'chat-online':'chat-offline'}"><span class="ti ti-circle"></span> ${val.status}</div>
                            </div>
                        </div>
                    </button>
                    `;
                });
                $("#chat-list").html(list);

                if (req.data.length == 0) {
                    $("#chat-list").html('Tidak ada chat!')
                }

                $(".btn-chat-list").on('click', function(e) {
                    e.preventDefault()
                    let id = $(this).attr('data-id')
                    clearInterval(get_interval)
                    get_interval = setInterval(function () {
                        getChat(id)
                    }, 3000)
                    $('#nama-channel').html($(this).attr('data-nama'))
                    $('#foto-channel').attr('src', "{{url('')}}"+$(this).attr('data-foto'))
                    $(".chat-messages").html("<h1>Memuat Pesan...<h1>");
                    $('.send-chat').addClass('d-none')
                    $('.send-message').attr('data-id', $(this).attr('data-id'))
                })
            })
        }

        get_list_chat();

        $("#search-chat").on('keyup', function (){
            get_list_chat($(this).val())
        })

        function getChat(id) {
            let url = "{{ route('api.messages.index', ':chat_id') }}";
            url = url.replace(':chat_id', id);
            callApi("GET", url, param, function (req) {
                let message = ''
                $.each(req.data, function (index, val) {
                    if (val.sender == 1) {
                        message += `
                            <div class="chat-message-right pb-4">
								<div>
									<img src="{{url('')}}${val.foto}" class="rounded-circle mr-1" alt="Anda" width="40" height="40">
                                    </div>
                                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                    <div class="font-weight-bold mb-1">
                                        <b>Anda (${val.role})</b>
                                    </div>
                                    ${val.message}
                                    <div class="text-end text-muted small text-nowrap mt-2">
                                        <i>${moment(val.created_at).format('YYYY-MM-DD HH:mm:ss')}</i>
                                    </div>
								</div>
							</div>
                        `;
                    } else {
                        message += `
                            <div class="chat-message-left pb-4">
								<div>
									<img src="{{url('')}}${val.foto}" class="rounded-circle mr-1" alt="${val.nama}" width="40" height="40">
                                    </div>
                                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                                    <div class="font-weight-bold mb-1">
                                        <b>${val.nama} (${val.role})</b>
                                    </div>
                                    ${val.message}
                                    <div class="text-end text-muted small text-nowrap mt-2">
                                        <i>${moment(val.created_at).format('YYYY-MM-DD HH:mm:ss')}</i>
                                    </div>
								</div>
							</div>
                        `;
                    }
                });

                $(".chat-messages").html(message);
                $('.send-chat').removeClass('d-none')
                if (counting_chat != req.data.length) {
                    let elem = $('.chat-messages');
                    elem.animate({ scrollTop: elem.prop("scrollHeight") }, "slow");
                }

                counting_chat = req.data.length

            })
        }

        $(".send-message").on('click', function(e) {
            e.preventDefault()
            let message = $('#message-text').val()
            let id = $(this).attr('data-id')

            data = {
                message: message
            }

            $('#message-text').val('')

            let url = "{{ route('api.messages.store', ':chat_id') }}";
            url = url.replace(':chat_id', id);

            callApi("POST", url, data, function (req) {
                pesan = req.message;
                if (req.error == true) {
                    Swal.fire(
                    'Gagal mengirim pesan!',
                    pesan,
                    'error'
                    )
                }else{
                    getChat(id);
                }
            })
        })
    })
</script>
@endpush
