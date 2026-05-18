@php
    //Arreglo de iconos
    $links= [
      [
         'name' => 'Panel administrativo',
         'icon' => 'fa-solid fa-gauge',   
         'href' => route('admin.dashboard'),
         'active' => request()->routeIs('admin.dashboard'),
      ],
      [
         'header' => 'Gestion',
         'can' => 'Administrador', // Solo admin ve el header de gestión general
      ],
      [
         'name' => 'Roles y permisos',
         'icon' => 'fa-solid fa-user-shield',
         'href' => route('admin.roles.index'),
         'active' => request()->routeIs('admin.roles.*'),
         'can' => 'Administrador',
      ],
      [
         'name' => 'Cajeros',
         'icon' => 'fa-solid fa-cash-register',
         'href' => route('admin.cajeros.index'),
         'active' => request()->routeIs('admin.cajeros.*'),
         'can' => ['Administrador', 'Cajeros'], // Admin y Cajeros pueden ver la sección
      ],
      [
         'name' => 'Cocineros',
         'icon' => 'fa-solid fa-utensils',
         'href' => '#',
         'active' => request()->routeIs('admin.cocineros.*'),
         'can' => ['Administrador', 'Cocineros'],
         'submenu' => [
            [
               'name' => 'Ver pedidos',
               'href' => route('admin.cocineros.pedidos.index'),
            ],
            [
               'name' => 'Inventario',
               'href' => route('admin.cocineros.inventario'),
            ],
         ],
      ],
      [
         'name' => 'Administrador',
         'icon' => 'fa-solid fa-user-tie',
         'href' => route('admin.administrador.index'),
         'active' => request()->routeIs('admin.administrador.*'),
         'can' => 'Administrador',
         'submenu' => [
            [
               'name' => 'Pedidos del dia',
               'href' => route('admin.administrador.pedidos'),
            ],
            [
               'name' => 'Inventario',
               'href' => route('admin.administrador.inventario'),
            ],
            [
               'name' => 'Registrar usuarios',
               'href' => route('admin.administrador.usuarios'),
            ],
            [
                'name' => 'Productos',
                'href' => route('admin.administrador.productos'),
            ]
         ],
      ],
    ];
@endphp

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-neutral-primary-soft border-e border-default">
      <div class="flex flex-col items-center mb-6 pt-2">
         <img src="{{ asset('assets/logo.png') }}" class="h-20 w-20 mb-3 rounded-full shadow-md object-cover border-2 border-white" alt="BubbaLova Logo" />
         <span class="text-xl font-black text-gray-800 tracking-tight">BubbaLova</span>
         <div class="h-1 w-12 bg-indigo-500 rounded-full mt-1"></div>
      </div>
      <ul class="space-y-2 font-medium">
         @foreach ($links as $link)
            @php
                $show = true;
                if (isset($link['can'])) {
                    if (is_array($link['can'])) {
                        $show = auth()->user()->hasAnyRole($link['can']);
                    } else {
                        $show = auth()->user()->hasRole($link['can']);
                    }
                }
            @endphp

            @if($show)
            <li>
               {{--- Revisa si existe una llave/propiedad llamada 'header' ---}}
               @isset($link['header'])
                  <div class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase">
                     {{ $link['header']}}
                  </div>
               @else 
                  {{--Revisa si existe el submenu--}}  
                  @isset($link['submenu'])
                     <li>
                        <button type="button" class="flex items-center w-full justify-between px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group" aria-controls="dropdown-{{ \Illuminate\Support\Str::slug($link['name']) }}" data-collapse-toggle="dropdown-{{ \Illuminate\Support\Str::slug($link['name']) }}">
                           <span class="w-6 h-6 inline-flex items-center justify-center text-gray-500">
                           <i class=" {{ $link['icon'] }} " ></i> </span>
                           <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $link['name'] }}</span>
                           <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                        </button>
                        
                        <ul id="dropdown-{{ \Illuminate\Support\Str::slug($link['name']) }}" class="hidden py-2 space-y-2">
                           @foreach ($link['submenu'] as $item)
                              <li>
                                 <a href="{{ $item['href'] }}" 
                                 class="pl-10 flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">{{ $item['name'] }}</a>
                              </li>
                           @endforeach
                        </ul>
                     </li>
                  @else
                     <a href="{{$link['href']}}" 
                        class="flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group {{$link['active'] ? 'bg-gray-100' : '' }}">
                           <span class="w-6 h-6 inline-flex items-center justify-center text-gray-500">
                           <i class=" {{ $link['icon'] }} " ></i> </span>
                        <span class="ms-3">{{$link['name']}}</span>
                     </a>
                  @endisset
               @endisset
            </li>
            @endif
         @endforeach

      </ul>
   </div>
</aside>