<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Phone') }}
        </h2>
    </x-slot>

    <div class="card my-4">
        <div class="card-body">
            <form method="POST" action="{{route('users.update', $phone->id)}}">
                @csrf
                <input type="hidden" name="_method" value="put" />

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" value="{{$phone->phone}}" name="phone" placeholder="phone">
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
