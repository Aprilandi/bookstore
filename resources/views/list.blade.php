@extends('default')
@section('content')
    <div class="filter">
        <table>
            <tr>
                <td>
                    List Shown
                </td>
                <td>:</td>
                <td>
                    <input type="text" name="search" id="search">
                </td>
            </tr>
            <tr>
                <td>
                    Search
                </td>
                <td>:</td>
                <td>
                    <select name="length" id="length" class="width: auto;">
                        @for($i = 10; $i <= 100; $i += 10)
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <button type="button" id="filter">Submit</button>
                </td>
            </tr>
        </table>
    </div>
    <div class="table">
        <table id="books-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Average Rating</th>
                    <th>Voter</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const table = $('#books-table').DataTable({
            processing: true,
            serverSide: true,
            dom: 'rtip',
            ajax: {
                url: "{{ route('books.data') }}",
                type: 'POST',
                data: function (d) {
                    d._token = '{{ csrf_token() }}';
                    d.search = {
                        value: $('input#search').val(),
                        regex: false
                    };
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title', orderable: false},
                { data: 'author', name: 'author', orderable: false},
                { data: 'category', name: 'category', orderable: false},
                { data: 'average_rating', name: 'average_rating', orderable: false},
                { data: 'total_votes', name: 'voter', orderable: false}
            ]
        });

        $('#filter').click(function() {
            const length = parseInt($('select#length').val());
    
            table.page.len( length ).draw(false);
        });
    });
</script>
@endpush