@php
    $status = false;
    $dataObj = json_decode($getState());
    $statusNum = null;
    if ($dataObj && is_object($dataObj)) {
        $statusNum = $dataObj->status ?? null;
        if ($dataObj->status == 1) {
            $status = true;
        }
        $data = $dataObj->data;
    } else {
        $data = $dataObj;
    }
@endphp
<div data-status-num="{{ $statusNum }}"
    style="@if ($statusNum === 1) background-color: #28a745; color: white; @elseif ($statusNum === 2) background-color: #dc3545; color: white; @else background-color: #dc3545; color: white; @endif padding: 0.25rem 0.5rem; border-radius: 0.25rem; display: inline-block; font-size: 0.75rem; font-weight: 700;">
    @php
        echo $data;
    @endphp
</div>
<script>
    (function() {
        function applyRowColors() {
            try {
                document.querySelectorAll('[data-status-num]').forEach((cell) => {
                    const status = String(cell.getAttribute('data-status-num'));

                    // Prefer closest <tr> (table mode)
                    let targetRow = cell.closest('tr');

                    // Otherwise look for Livewire wrapper
                    const recordRoot = cell.closest('[wire\\:key]');
                    if (!targetRow && recordRoot) {
                        if (recordRoot.tagName === 'DIV') {
                            const inner = recordRoot.querySelector(':scope > div');
                            targetRow = inner || recordRoot;
                        } else {
                            targetRow = recordRoot;
                        }
                    }

                    // Fallback: if inside a <td>, use its parent <tr>
                    if (!targetRow) {
                        const td = cell.closest('td');
                        if (td && td.parentElement && td.parentElement.tagName === 'TR') {
                            targetRow = td.parentElement;
                        }
                    }

                    if (!targetRow) return;

                    let color = '';
                    if (status === '1') {
                        color = '#ecfdf5'; // green-50
                    } else if (status === '2') {
                        color = '#fee2e2'; // red-50
                    } else {
                        color = '#fee2e2'; // red-50
                    }

                    // If targetRow is a table row, color all <td> children; otherwise color the element itself
                    if (targetRow.tagName === 'TR') {
                        targetRow.style.transition = 'background-color .15s ease-in-out';
                        targetRow.querySelectorAll('td').forEach((td) => {
                            td.style.backgroundColor = color;
                        });
                    } else {
                        targetRow.style.backgroundColor = color;
                        targetRow.style.transition = 'background-color .15s ease-in-out';
                    }
                });
            } catch (e) {
                // ignore
            }
        }

        // apply immediately
        applyRowColors();

        // re-apply when Livewire updates
        window.addEventListener('livewire:update', applyRowColors);
        document.addEventListener('livewire:update', applyRowColors);

        // also re-apply when Livewire finishes morphdom updates
        document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', () => {
                applyRowColors();
            });
        });

        // fallback: observe DOM changes under the table
        const root = document.querySelector('.filament-tables-component');
        if (root) {
            const obs = new MutationObserver(() => applyRowColors());
            obs.observe(root, {
                childList: true,
                subtree: true
            });
        }
    })();
</script>
