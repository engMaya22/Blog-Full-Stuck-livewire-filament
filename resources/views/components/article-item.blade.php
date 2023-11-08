<article class="flex flex-col shadow my-4">
    <!-- Article Image -->
    <a href="{{route('view', $article)}}" class="hover:opacity-75">
        <img src="{{$article->getImage()}}" alt="{{$article->title}}">
    </a>
    <div class="bg-white flex flex-col justify-start p-6">
        <a href="{{route('view', $article)}}" class="text-blue-700 text-sm font-bold uppercase pb-4">{{$article->category->name}}</a>
        <a href="{{route('view', $article)}}" class="text-3xl font-bold hover:text-gray-700 pb-4">{{$article->title}}</a>
        <p href="#" class="text-sm pb-3">
            By <a href="#" class="font-semibold hover:text-gray-800">{{$article->user->name}}</a>, Published On : {{$article->getFormattedDate()}}
        </p>
        <a href="#" class="pb-6">{!! $article->shortBody() !!}</a>
        <a href="{{route('view', $article)}}" class="uppercase text-gray-800 hover:text-black">Continue Reading <i class="fas fa-arrow-right"></i></a>
    </div>
  
</article>