<x-app-layout meta-title="TheCodeholic Blog"
              meta-description="Lorem ipsum dolor sit amet, consectetur adipisicing elit">
    <div class="container max-w-4xl mx-auto py-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Latest article -->
            <div class="col-span-2">
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                    Latest Article
                </h2>

                @if ($latestArticle)
                    <x-article-item :article="$latestArticle"/>
                @endif
            </div>

            <!-- Popular 5 articles -->
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                    Popular Articles
                </h2>
                @foreach($popularArticles as $article)
                    <div class="grid grid-cols-4 gap-2 mb-4">
                        <a href="{{route('view', $article)}}" class="pt-1">
                            <img src="{{$article->getImage()}}" alt="{{$article->title}}"/>
                        </a>
                        <div class="col-span-3">
                            <a href="{{route('view', $article)}}">
                                <h3 class="text-sm uppercase whitespace-nowrap truncate">{{$article->title}}</h3>
                            </a>
                            <div class="flex gap-4 mb-2">
                                
                                    <a href="#" class="bg-blue-500 text-white p-1 rounded text-xs font-bold uppercase">
                                        {{$article->category->name}}
                                    </a>
                             
                            </div>
                            <div class="text-xs">
                                {{$article->shortBody(10)}}
                            </div>
                            <a href="{{route('view', $article)}}" class="text-xs uppercase text-gray-800 hover:text-black">Continue
                                Reading <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    {{-- <x-article-item :article="$article"/>  for desgin purposes i dont use this component here--}} 
                @endforeach
            </div>
        </div>

        <!-- Recommended posts -->
      <div class="mb-8">
            <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                Recommended Articles
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($recommendedArticles as $article)
                <x-article-item :article="$article" :show-author="false"/>
                    {{-- 
                     in construct of class component we can make
                    show-author is true and when we want to make this author hidden we chacke thid show if true in  blade of 
                    component we present it --}}

                @endforeach
            </div>
        </div>

        <!-- Latest Categories -->

        @foreach($categories as $category)
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                    Category "{{$category->title}}"
                    <a href="{{route('by-category', $category)}}">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </h2>

                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach($category->publishedArticles()->limit(3)->get() as $article)
                            <x-article-item :article="$article" :show-author="false"/>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach 

    </div>
</x-app-layout>
