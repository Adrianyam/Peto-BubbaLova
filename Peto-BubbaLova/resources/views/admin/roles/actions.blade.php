<div class="flex items-center space-x-2">
    <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center p-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    
    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" data-swal-confirm>
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center p-1.5 bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition-colors">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>