@extends('default')

@section('content')
    <h1>Top 10 Famous Authors</h1>
    <div class="table">
        <table id="top-books-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Author Name</th>
                    <th>Voter</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['total_ratings'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#top-books-table').DataTable({
            paging: false,
            searching: false,
            ordering: false,
            info: true
        });
    });
</script>
@endpush
