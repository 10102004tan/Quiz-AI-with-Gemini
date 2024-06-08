@extends('layouts.app')
@section('content')
<div class="shadow-bar sticky top-0 left-0 right-0 bg-primary z-[999]">
    <div class="container">
        <div>
            @isset($quiz)
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="inline-block py-5 font-[500] text-white">{{$quiz->title}}</h2>
                    <button type="button" class="btn-edit-quiz"><i class="fa-light fa-pen-to-square"></i></button>
                </div>

                <div class="flex items-center gap-2">
                <button data-modal-target="default-modal" data-modal-toggle="default-modal" id="btn-share" type="button" class="flex items-center py-1 px-2 rounded border border-[#eee]">
                <i class="fa-sharp fa-regular fa-share-nodes p-2"></i>
                    Share
                    </button>
                    <button id="btn-settings" type="button" class="flex items-center py-1 px-2 rounded border border-[#eee]">
                    <i class="fa-regular fa-gear p-2"></i>
                    Setting
                    </button>
                    <button type="button" class="py-2 px-2 rounded bg-blue-500">
                    Play
                    </button>

                    @if ($quiz->status == 0)
                    <button quizId="{{$quiz->id}}" type="button" class="py-2 px-2 rounded btn-published bg-green-500">
                        Publish
                    </button>
                    @elseif($quiz->status == 1)
                    <button type="button" class="py-2 px-2 rounded bg-yellow-200">
                        Pendding
                    </button>
                    @elseif($quiz->status == 3)
                    <button type="button" class="py-2 px-2 rounded bg-red-500">
                        Reject
                    </button>
                    @endif
                </div>
            </div>
            <!-- edit -->
            <div class="bg-overlay fixed z-[999] w-[100vw] h-[100vh] top-0 invisible opacity-0 left-0  modal-edit-quiz">
                <form class="py-4 px-5 border-[1px] border-gray-200 rounded-[10px] center md:w-[60%] w-[80%] bg-primary modal-update-quiz">
                    <div class="flex justify-between items-center">
                        <h2 class="text-[26px]">Edit Title and Description</h2>
                        <button type="button" class="btn-close-modal-edit-quiz"><i class="fa-light fa-xmark"></i></button>
                    </div>
                    <input type="hidden" name="quiz_id" value="{{$quiz->id}}">
                    <x-inputs.input class="input-quiz-title" title="Title" placeholder="Enter title" name="title" row="1">{{$quiz->title}}</x-inputs.input>
                    <x-inputs.input class="input-quiz-description" title="Description" placeholder="Enter description" row="3" name="description">{{$quiz->description}}</x-inputs.input>
                    <div class="mt-[80px] flex justify-end items-center gap-3">
                        <x-buttons.secondary>View</x-buttons.secondary>
                        <x-buttons.primary class="btn-update-quiz">Update</x-buttons.primary>
                    </div>
                </form>
            </div>
            @else
            <h2 class="inline-block py-5 font-[500] text-white">Add Questions</h2>
            @endisset
        </div>
    </div>
</div>

<!-- settings start -->
<div class="settings lg:w-[20vw] w-[40vw] z-[9999] h-[100vh] fixed top-0 right-[-100%] bg-primary p-3">
   <div class="flex justify-between">
   <h2>Default setting</h2>
    <button type="button" class="btn-close-settings"><i class="fa-light fa-xmark"></i></button>
   </div>
   <hr>
   <ul>
    <li>Share</li>
    <li>Behaviour</li>
    <li>Share</li>
   </ul>
</div>
<!-- settings end -->

<!-- main start-->
@if(!session('error'))
<section>
    <div class="grid grid-cols-12">
        <div class="px-[2rem] py-4 create bg-primary relative lg:col-span-4 col-span-12">
            <div class="flex gap-4 border-b-[1px] border-gray-400">
                <button type="button" option-data="0" class="active py-3 border-b-[2px] border-transparent active:border-blue-700 active:text-blue-700 hover:border-slate-500 hover:text-slate-500 text-white show-option">Text</button>
                <button type="button" option-data="1" class="py-3 border-b-[2px] border-transparent hover:border-slate-500 hover:text-slate-500 text-white show-option">Manual</button>
            </div>
            @isset($quiz)
                <livewire:form-create-quiz-a-i :quiz_id="$quiz->id" />
                <livewire:form-create-quiz-manual :quiz_id="$quiz->id" />
            @else
                <livewire:form-create-quiz-a-i />
                <livewire:form-create-quiz-manual />
            @endisset
           
        </div>
        <div class="result lg:col-span-8 py-4 px-5 bg-secondary relative col-span-12">
            @isset($quiz)
            <div class=" result-questions md:mb-5">
                <div class="intro-binding flex justify-between p-3 mb-5 rounded bg-blue-500">
                    <div class="pb-[30px]">
                        <h5 class="mb-2 text-[24px]">Study better</h5>
                        <p>Unlimited quizzes, unlimited questions. Upload long files, scan your notes and more</p>
                    </div>
                    <span class="font-bold text-[18px] mt-auto">View Deal</span>
                </div>

                <!-- thumb -->
                <div class="thumb overflow-hidden mb-5">
                    <img class="w-[100%] rounded-[10px] object-cover h-[300px]" src="https://www.cshl.edu/wp-content/uploads/2023/01/cute_robot_reading_book.jpg" alt="">
                </div>

                <!-- bar -->
                <div class="bar mb-5">
                    <div class="flex gap-3">
                        <button class="bg-blue-500 text-white p-2 rounded-[5px]">Questions</button>
                        <button class="bg-red-500 text-white p-2 rounded-[5px]">Study Notes</button>
                    </div>
                </div>

                <!-- options -->
                <div class="flex justify-end mb-5">
                    <div class="flex gap-3">
                        <x-buttons.primary class="bg-blue-500 text-white p-2 rounded-[5px]">Show answer</x-buttons.primary>
                        <select class="bg-primary p-3 rounded-[10px] text-white border-2 border-gray-400">
                            <option value="" disabled selected>Export</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                </div>
                <livewire:list-questions :quiz="$quiz" />
            </div>
            @else
            <!-- first -->
            <div class="p-5 rounded bg-primary w-[50%] mx-auto lg:mt-[150px] mt-3 relative result-intro">
                <h2 class="mb-2 text-[26px]">Generate quizzes</h2>
                <p>Generate quizzes from your notes, study materials, or any text you have. You can also create quizzes manually.<i class="fa-duotone fa-microchip-ai text-yellow-400 text-[20px]"></i> <i class="fa-duotone fa-cloud-bolt text-yellow-400 text-[20px]"></i> </p>
            </div>
            @endisset
        </div>
    </div>
</section>
@else
<div class="bg-primary text-white p-5">
    <h2 class="text-[26px]">Error</h2>
    <p>{{session('error')}}</p>
</div>
@endif

@endsection
@section('script')
<script>
    window.routes = {
        quizzesQuestionsStoreAI: "{{ route('quizzes.storeWithAI') }}",
        quizzesUpdate: "{{ route('quizzes.update')}}",
        quizzesQuestionDestroy: "{{ route('quizzes.question.destroy') }}",
        quizzesQuestionUpdate: "{{route('quizzes.question.update')}}",
        quizzesQuestionStore: "{{route('quizzes.question.store')}}",
        quizzesPublished: "{{route('quizzes.published')}}",
    };

    document.addEventListener('livewire:init', () => {
        Livewire.on('toast', ({message,status}) => {
            Swal.fire({
                title: 'Thông báo',
                text: message,
                icon: status,
                confirmButtonText: 'OK'
            })
       });

       Livewire.on('toast-manual', ({message,status}) => {
           Toastify({
               text: message,
               duration: 3000,
               newWindow: true,
               close: true,
               gravity: 'top',
               position: 'right',
               backgroundColor: status == 'success' ? 'linear-gradient(to right, #00b09b, #96c93d)' : 'linear-gradient(to right, #ff5f6d, #ffc371)',
               stopOnFocus: true,
           }).showToast();
       });
    }); 
</script>
<script src="{{ asset('js/app.js') }}"></script>
@endsection