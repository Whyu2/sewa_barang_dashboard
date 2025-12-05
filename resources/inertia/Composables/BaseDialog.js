import { markRaw } from 'vue';
import { useDialog } from 'primevue/usedialog';
import BaseDialog from '@/inertia/Components/BaseDialog.vue';

export function baseDialog() {
  const dialog = useDialog();

  function openBaseDialog({
    component,
    componentProps = {},
    titleHeader,
    position = 'top',
    width = `50vh`,
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
        header: titleHeader ?? ' ',
        position: position,
        style: { width: width },
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
