import { Controller } from 'stimulus';
import Dropzone from 'dropzone';

export default class extends Controller {
  static targets = ['dropzone', 'imagesInput', 'imagesToDeleteInput'];
  static values = {
    url: String,
    maxfilesize: String,
    maxfiles: String,
    existingfiles: Array,
  }

  connect() {
    Dropzone.autoDiscover = false;

    const maxFilesize = this.data.get('maxFilesize');
    const maxFiles = this.data.get('maxFiles');

    this.dropzone = new Dropzone(this.dropzoneTarget, {
      url: '/',
      autoProcessQueue: false,
      uploadMultiple: true,
      paramName: 'product[images]',
      parallelUploads: 4,
      maxFilesize: maxFilesize,
      maxFiles: maxFiles,
      acceptedFiles: 'image/*',
      addRemoveLinks: true,
      dictRemoveFile: 'Remove',
      dictFileTooBig: `File is too big. Max filesize: ${maxFilesize}MB.`,
      dictMaxFilesExceeded: `You can only upload ${maxFiles} files.`,
    });

    this.setupExistingFiles();
    this.setupFileManagement();
  }

  setupExistingFiles() {
    this.existingfilesValue.forEach((file) => {
      const mockFile = { id: file.id, name: file.name, size: file.size };
      this.dropzone.emit('addedfile', mockFile);
      this.dropzone.emit('thumbnail', mockFile, file.url);
      this.dropzone.emit('complete', mockFile);
    });
  }

  setupFileManagement() {
    const removedFileIds = [];

    this.dropzone.on('removedfile', (file) => {
      const existingFile = this.existingfilesValue.find((existingFile) => existingFile.id === file.id)

      if (existingFile) {
        removedFileIds.push(existingFile.id);
        this.imagesToDeleteInputTarget.value = removedFileIds.join(',');
      }
    });

    this.dropzone.on('addedfile', (file) => {
      const dataTransfer = new DataTransfer();
      Array.from(this.imagesInputTarget.files).forEach((f) => {
        dataTransfer.items.add(f);
      });
      dataTransfer.items.add(file);
      this.imagesInputTarget.files = dataTransfer.files;
    });

    this.dropzone.on('removedfile', (file) => {
      const dataTransfer = new DataTransfer();
      Array.from(this.imagesInputTarget.files).forEach((f) => {
        if (f.name !== file.name) {
          dataTransfer.items.add(f);
        }
      });
      this.imagesInputTarget.files = dataTransfer.files;
    });
  }
}
