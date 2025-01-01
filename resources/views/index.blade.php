<x-layout>
  <h1 class="text-2xl font-semibold text-center p-4">Driving Theory Test - Testy Teoretyczne Na Prawo Jazdy!!</h1>
  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-4 text-center">
    @foreach ($categories as $category)
      <a
        href="/"
        class="sm:max-w-sm p-6 border border-gray-200 rounded-lg shadow hover:bg-gray-200">
        <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
        <p class="text-gray-700"></p>
      </a>
    @endforeach
  </div>
</x-layout>