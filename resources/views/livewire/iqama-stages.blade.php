<div>
    @php
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp
    <div class="m-3 mb-4">
        <label for="iqamaType" class="block mb-2 font-semibold">{{ __('Select Iqama Type') }}</label>
        <select wire:model.change="selectType" id="iqamaType" class="w-full p-2 border rounded">
            <option value="">{{ __('Choose Type') }}</option>
            @foreach ($types as $type)
                <option value="{{ $type->id }}">{{ $type->getTranslation('name', app()->getLocale()) }}</option>
            @endforeach
        </select>
    </div>
    @if ($stages)
        <div class="m-2 card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Order') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Cost') }}</th>
                            <th>{{ __('Estimated Time') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('File') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($stages as $stage)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>
                                    {{ $stage->name }}<br>
                                    {{ $stage->getTranslation('name', $rev_locale) }}
                                </td>
                                <td>
                                    {{ $stage->description }}
                                    <br>
                                    {{ $stage->getTranslation('description', $rev_locale) }}
                                </td>
                                <td>{{ $stage->price }}</td>
                                @if(!empty($stage->cost))
                                    <td>{{ $stage->cost }}</td>
                                @else
                                    <td>{{ __('Not Specified') }}</td>
                                @endif
                                @if ($stage->estimated_time_in_days)
                                    <td>{{ $stage->estimated_time_in_days . ' ' . __('Days') }}</td>
                                @else
                                    <td>{{ __('Not Specified') }}</td>
                                @endif
                                @if (!is_null($stage->image))
                                    <td><img src="{{ asset('storage/' . $stage->image) }}" alt=""
                                            class="w_200">
                                    </td>
                                @else
                                    <td>
                                        <p>{{ __('No image') }}</p>
                                    </td>
                                @endif
                                @if (!is_null($stage->file))
                                    <td class="d-flex" style="row-gap: 10px; flex-direction:column">
                                        <iframe src="{{ asset('storage/' . $stage->file) }}" width="100%"
                                            style="border: none;"></iframe>

                                        <a href="{{ asset('storage/' . $stage->file) }}" class="btn btn-success btn-sm text-center"
                                            download>
                                            {{ __('Download PDF') }}
                                        </a>

                                    </td>
                                @else
                                    <td>
                                        <p>{{ __('No file') }}</p>
                                    </td>
                                @endif
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admins.stages.edit', $stage->id) }}"
                                            class="mx-1 btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admins.stages.destroy', $stage->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('Are you sure?') }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="py-2 d-flex justify-content-center">
            {{ $stages->links() }}
        </div>
    @else
        <div class="mx-2 alert alert-info">
            {{ __('No stages found.') }}
        </div>
    @endif

    {{-- <script>
        document.addEventListener('livewire:initialized', () => {
            console.log(1);
            renderPdfs();
        });
        document.addEventListener('livewire:updated', () => {
            console.log(1);
            renderPdfs();
        });

        function renderPdfs() {

            console.log(2);
            pdfjsLib.GlobalWorkerOptions.workerSrc =
                'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

            $('.pdf-viewer').each(function() {
                let container = $(this);
                console.log(container);
                let url = container.attr('url');
                console.log(url);

                if (url && !container.find('canvas').length) {
                    const loadingTask = pdfjsLib.getDocument(url);
                    loadingTask.promise.then(pdf => {
                        pdf.getPage(1).then(page => {
                            const scale = 1.5;
                            const viewport = page.getViewport({
                                scale
                            });
                            const canvas = document.createElement("canvas");
                            const context = canvas.getContext("2d");

                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            container.append(canvas);

                            page.render({
                                canvasContext: context,
                                viewport
                            });
                        });
                    }).catch(error => {
                        console.error('Error loading PDF:', error);
                        container.text('Failed to load PDF preview.');
                    });
                }
            });
        }
    </script> --}}

</div>
