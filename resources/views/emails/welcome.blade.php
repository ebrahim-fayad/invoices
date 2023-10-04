<x-mail::message>
<h3>Hello!</h3>
<p>تمت اضافة فاتورة جديدة</p>


{{--  <x-mail::button :href="'{{ route('routeName', ['id'=>1]) }}'">  --}}
  <a style="text-decoration: none; display: block; width: fit-content; padding: 2px 4px;border-radius: 12px; background-color: #202438; color: white; margin:  10px auto;font-weight:bold;"  href="{{ route('invoicesDetails.show', $invoice->id) }}">

    عرض الفاتورة الجديدة
  </a>
{{--  </x-mail::button>  --}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
