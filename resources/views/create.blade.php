<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Create Phone') }}
        </h2>
    </x-slot>

    <div class="card my-4">
        <div class="card-body">
            @if ($message = Session::get('success'))

                <div class="alert alert-success alert-block">
                    <strong>{{ $message }}</strong>
                </div>

            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{old('phone')}}" placeholder="phone">
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
