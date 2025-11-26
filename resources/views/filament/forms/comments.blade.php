<section class="py-24 relative">
        <div class="w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto">
            <div class="w-full flex-col justify-start items-start lg:gap-14 gap-7 inline-flex">
                <div class="w-full flex-col justify-start items-start gap-8 flex">
                   @forelse (\App\Models\StudentComment::where('student_id', $getRecord()->id)->get() as $item)
                        <div
                        class="w-full lg:p-8 p-5 bg-white rounded-3xl border border-gray-200 flex-col justify-start items-start flex">
                        <div class="w-full flex-col justify-start items-start gap-3.5 flex">
                            <div class="w-full justify-between items-center inline-flex">
                                <div class="justify-start items-center space-x-3 flex">
                                    <div
                                        class="w-10 h-10 bg-stone-300 rounded-full justify-start items-start gap-2.5 flex">
                                        <img class="rounded-full object-cover" src="{{asset('images/student.png')}}"
                                            alt="John smith image" />
                                    </div>
                                    <div class="flex-col justify-start items-start gap-1 inline-flex">
                                        <h5 class="text-gray-900 text-sm font-semibold leading-snug">Guidance Personel</h5>
                                        <h6 class="text-gray-500 text-xs font-normal leading-5">{{$item->created_at->diffForHumans()}}</h6>
                                    </div>
                                </div>
                              
                            </div>
                            <p class="text-gray-800 text-sm text-justify font-normal leading-snug">
                               {{$item->comment}}</p>
                        </div>
                    </div>
                   @empty
                       
                   @endforelse
                  
                
                </div>
            </div>
        </div>
    </section>