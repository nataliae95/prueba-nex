<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="client-show-app" data-id="{{ $id }}">
                <client-show :client-id="{{ $id }}"></client-show>
            </div>
        </div>
    </div>
</x-app-layout>