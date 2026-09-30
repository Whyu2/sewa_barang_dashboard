import { markRaw } from 'vue';
import { useDialog } from 'primevue/usedialog';
import BaseDialog from '@/inertia/Components/BaseDialog.vue';

export function baseDialog() {
  const dialog = useDialog();

  function openBaseDialog({
    component,
    componentProps = {},
    titleHeader,
    position = 'center',
    width = '640px',
    onClose = () => {},
    onSubmit = () => {},
    onCancel = () => {},
    onUpdate = () => {},
  }) {
    dialog.open(BaseDialog, {
      data: {
        component: markRaw(component),
        componentProps,
      },
      props: {
        modal: true,
        draggable: false,
        dismissableMask: false,
        closeOnEscape: false,
        closable: true,
        header: titleHeader ?? ' ',
        position: position,
        breakpoints: { '960px': '90vw' },
        style: { width: width, maxWidth: '720px', borderRadius: '24px', border: 'none', overflow: 'hidden' },
        pt: { header: { style: { padding: '20px 24px 12px 24px', border: 'none' } }, content: { style: { padding: '0 24px 24px 24px' } } },
      },
      onClose: ({ data }) => {
        if (typeof data?.action === 'undefined') {
          onClose();
        }
      },
      emits: {
        onSubmit: args => onSubmit(args),
        onCancel: args => onCancel(args),
        onClose: args => onClose(args),
        onUpdate: args => onUpdate(args),
      },
    });
  }

  return {
    openBaseDialog,
  };
}
