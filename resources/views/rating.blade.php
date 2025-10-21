@extends('default')

@section('content')
    <h1>Insert Rating</h1>
    {{-- @php(dd($success)) --}}
    @if(session('success'))
        <div style="padding: 10px; background-color: blue; color: white; border: 1px solid black; border-radius: 4px; margin-bottom: 16px;">
            {{ session('success') }}
        </div>
    @endisset
    <form action="{{ route('rating.submit') }}" method="post" style="display: flex; flex-direction: column; gap: 12px; max-width: 400px;">
        @csrf
        <div>
            <label for="author">Book Author :</label>
            <select name="author" id="author" style="width:100%;"></select>
        </div>
        <div>
            <label for="book">Book Name :</label>
            <select name="book" id="book" style="width:100%;"></select>
        </div>
        <div>
            <label for="rating">Rating :</label>
            <select name="rating" id="rating" style="width:100%;">
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div>
            <button type="submit">Submit Rating</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#author').select2({
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("authors.list") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                }
            });

            $('#book').select2({
                ajax: {
                    url: '{{ route("books.list") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1,
                            author_id: $('#author').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                }
            });

            $('#book').prop('disabled', true);

            $('#author').on('change', function() {
                $('#book').val(null).trigger('change');
                $('#book').prop('disabled', !$(this).val());
            });

        });
    </script>
@endpush