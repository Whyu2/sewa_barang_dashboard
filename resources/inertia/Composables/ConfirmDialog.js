import {useConfirm} from "primevue/useconfirm";
    const confirmDialog = () => {
    const confirm = useConfirm();
    const baseConfirmDialog =({
        message, header, acceptLabel, onAccept
    }) => {
        confirm.require({
            message: message,
            header: header,
            icon: 'pi pi-info-circle',
            rejectLabel: 'Cancel',
            rejectProps: {
                label: 'Cancel',
                severity: 'secondary',
                outlined: true
            },
            acceptProps: {
                label: acceptLabel,
                severity: 'danger'
            },
            accept: onAccept,
        });
    }

    return {baseConfirmDialog};

}

export default  confirmDialog;
