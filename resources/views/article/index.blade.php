<x-app-layout :meta-title="'Posts bleongs to category ' .$category->name" >
    <!-- Posts Section -->
 <section class="w-full md:w-2/3 flex flex-col items-center px-3">

   
    @foreach ($articles as $article )
        <x-article-item :article="$article" />

    @endforeach
    {{$articles->links()}}

   

</section>
</x-app-layout>
{{-- AppLayout component is called by x-app-layout --}}