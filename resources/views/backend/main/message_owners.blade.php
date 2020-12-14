@extends('layouts.back_layout')
@section('back-style')

@endsection
@section('back-content')
    <section class="content">
        <div class="content__inner">
            <header class="content__title">
                <h1>{{ __('global.side.owner').' '.__('global.side.messages') }}</h1>
            </header>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center" id="own_message_table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>{{ __('global.common.subject') }}</th>
                                <th>{{ __('global.common.content') }}</th>
                                <th>{{ __('global.common.sender') }}</th>
                                <th>{{ __('global.common.datetime') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($o_messages as $index => $o_message)
                                <tr id="menu_{{ $o_message->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="subject">{{ $o_message->subject }}</td>
                                    <td class="content">{{ $o_message->content }}</td>
                                    <td class="sender">{{ $o_message->sender_id }}</td>
                                    <td class="datetime">{{ $o_message->created_at }}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('back-script')
    <script>
        $(function () {
            $("#own_message_table").DataTable({
                aaSorting: [],
                autoWidth: !1,
                responsive: !0,
                lengthMenu: [[15, 40, 100, -1], ["15 Rows", "40 Rows", "100 Rows", "Everything"]],
                language: {searchPlaceholder: "Search for records..."},
                sDom: '<"dataTables__top"flB<"dataTables_actions">>rt<"dataTables__bottom"ip><"clear">',
            });
        })
    </script>
@stop
