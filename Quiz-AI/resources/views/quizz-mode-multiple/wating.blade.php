@extends('layouts.link_script')

{{-- Body --}}
@section('content')
    <div class="w-full h-[100vh]  bg-[var(--background)]">
        {{-- Error notify --}}
        @if ($errors->any())
            <div class="mb-2 form_error_notify bg-white rounded-lg overflow-hidden">
                <span class="block w-full p-4 bg-red-500 text-white">Error</span>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-red-500 p-2">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- Success notify --}}
        @if (session('success'))
            <div class="form_success_notify">
                <div class="mb-2 form_error_notify bg-white rounded-lg overflow-hidden">
                    <span class="block w-full p-4 bg-green-500 text-white">Success</span>
                    <ul>
                        <li class="text-green-500 p-4">{{ session('success') }}</li>
                    </ul>
                </div>
            </div>
        @endif
        {{-- Header --}}
        <div class="bg-[var(--background-dark)] w-full p-2 flex items-center justify-between">
            <button
                class="btn_close_room p-4 rounded- flex items-center justify-center rounded-lg bg-[var(--input-form-bg)]">
                <i class="fa-solid fa-xmark-large"></i>
            </button>

            <p>Waiting for your friends to join the game...</p>

            <div>
                <button class="p-4 rounded- flex items-center justify-center rounded-lg bg-[var(--input-form-bg)]">
                    <i class="fa-regular fa-expand"></i>
                </button>
            </div>
        </div>
        {{-- Room info --}}
        <div class="p-4 mx-auto mt-4 w-fit min-w-[300px] rounded-lg bg-[var(--background-dark)]">
            <h5 class="text-sm mb-5 text-center">Ask your friends</h5>
            <div class="text-center">
                <h4 class="mb-2">
                    1. Open the browser to open
                </h4>
                <a href="#"
                    class="bg-[var(--text)] p-3 block rounded-lg text-[var(--background)] text-lg font-semibold">Join my
                    quiz.com</a>
            </div>

            <div class="text-center mt-4">
                <h4 class="mb-2">
                    2. Enter the room code
                </h4>
                <button class="bg-[var(--text)] p-3 w-full rounded-lg text-[var(--background)] text-lg font-semibold">0000
                    999 333</button>
            </div>
            <button
                class="mt-3 p-1 font-semibold rounded-lg underline text-center text-sm w-full bg-[var(--input-form-bg)]">
                Or share a link
            </button>
        </div>
        {{-- Line --}}
        <div class="relative w-full h-[1px] bg-[var(--background-dark)] mt-14">
            <div
                class="absolute top-[50%] left-[40px] translate-y-[-50%] flex items-center justify-center gap-2 p-2 rounded-lg bg-[var(--background-dark)] min-w-[120px] text-[var(--text)] font-semibold text-lg">
                <i class="fa-solid fa-user-group-simple"></i>
                <span class="amount_member">1</span>
            </div>
            <button
                class="absolute min-w-[120px] left-[50%] top-[50%] translate-x-[-50%] translate-y-[-50%] btn_shadow p-2 rounded-lg bg-[var(--text)] text-[var(--background)] font-semibold text-lg">
                Start
            </button>
        </div>

        {{-- Group member --}}
        <div class="group-member gap-2 mt-14 px-[40px] grid grid-cols-4">
        </div>
    </div>

@endsection
@section('script')
    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: 'ap1',
            encrypted: true
        });

        // Views
        const groupMember = document.querySelector('.group-member');
        const amoutMememberView = document.querySelector('.amount_member');
        const buttonCloseRoom = document.querySelector('.btn_close_room');
        let amount = 1;
        let members = [];
        members.push({
            id: '{{ Auth::user()->id }}',
            name: '{{ Auth::user()->name }}'
        });
        members.forEach(element => {
            groupMember.innerHTML += `
                <div class="px-4 py-8 rounded-3xl bg-[var(--background-dark)] flex items-center justify-between">
                    <div class="flex items-start justify-start flex-col">
                        <h3>${element.name}</h3>
                        <div class="w-fit rounded-3xl px-2 bg-[var(--input-form-bg)]">PlayerID: ${element.id}</div>
                    </div>
                    <div class="">
                        <img src="{{ asset('images/monster10.png') }}" alt="" class="w-[60px] h-[60px]">
                    </div>
                </div>
            `;
        })

        amoutMememberView.textContent = amount;

        // Bind a function to a Event (the full Laravel class)
        // Subscribe to the channel we specified in our Laravel Event
        const channelUserJoined = pusher.subscribe('UserJoinedRoom');
        channelUserJoined.bind('send-notify', function(data) {
            if (members.some(member => member.id === data.user.id)) return;
            members.push(data.user);
            amount++;
            amoutMememberView.textContent = amount;
            groupMember.innerHTML += `
            <div class="px-4 py-8 rounded-3xl bg-[var(--background-dark)] flex items-center justify-between">
                <div class="flex items-start justify-start flex-col">
                    <h3>${data.user.name}</h3>
                    <div class="w-fit rounded-3xl px-2 bg-[var(--input-form-bg)]">PlayerID: ${data.user.id}</div>
                </div>
                <div class="">
                    <img src="{{ asset('images/monster10.png') }}" alt="" class="w-[60px] h-[60px]">
                </div>
            </div>
            `;
        });

        const channelUserLeft = pusher.subscribe('UserLeftRoom');
        channelUserLeft.bind('send-notify', function(data) {
            members = members.filter(member => member.id !== data.user.id);
            amount--;
            amoutMememberView.textContent = amount;
            console.log(members);
            groupMember.innerHTML = '';
            members.forEach(element => {
                groupMember.innerHTML += `
                <div class="px-4 py-8 rounded-3xl bg-[var(--background-dark)] flex items-center justify-between">
                    <div class="flex items-start justify-start flex-col">
                        <h3>${element.name}</h3>
                        <div class="w-fit rounded-3xl px-2 bg-[var(--input-form-bg)]">PlayerID: ${element.id}</div>
                    </div>
                    <div class="">
                        <img src="{{ asset('images/monster10.png') }}" alt="" class="w-[60px] h-[60px]">
                    </div>
                </div>
            `;
            });
        });

        buttonCloseRoom.addEventListener('click', () => {
            window.location.href = '{{ route('quiz.multiple.left', 1) }}';
        });
    </script>
@endsection