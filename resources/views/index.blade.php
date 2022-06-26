<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('All Phones') }}
        </h2>
    </x-slot>

    <div class="card my-4">
        <div class="card-body">

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($phones as $phone)
                    <tr>
                        <th scope="row">{{$phone->id}}</th>
                        <td>{{$phone->phone}}</td>
                        <td class="d-flex">
                            <a class="btn btn-primary mx-2" href='users/{{ $phone->id }}/edit'>Edit</a>
                            <form class="ml-5" method="POST" action="/users/{{ $phone->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="delete" />
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
