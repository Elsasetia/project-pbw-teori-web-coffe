@props(['rating'])
<span class="text-amber-600" role="img" aria-label="{{ $rating }} dari 5 bintang">{{ str_repeat('★', $rating) }}<span class="text-stone-300">{{ str_repeat('★', 5 - $rating) }}</span></span>
