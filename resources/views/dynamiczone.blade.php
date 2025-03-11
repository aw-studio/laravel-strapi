@foreach ($items as $item)
    {{ $resolver->resolve($item) }}
@endforeach
