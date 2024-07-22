<x-app-layout>

    <div class="fixed w-full h-full flex bg-white border lg:shadow-sm overflow-hidden lg:top-16 lg:inset-x-2 m-auto lg:h-[90%] rounded-lg">
        <div class="relative w-full md:w-[320px] xl:w-[480px] overflow-y-auto shrink-0 h-full border">
            <livewire:chat.chat-list />
        </div>
        <div class="hidden md:grid w-full h-full relative overflow-y-auto" style="contain: content">
            <div class="m-auto text-center justify-center flex flex-col gap-3">
                <h4 class="font-medium text-lg">Talk to people!</h4>
            </div>

        </div>
    </div>
</x-app-layout>
