import { shallowMount, createLocalVue } from '@vue/test-utils';

import store from '@/store';
import router from '@/router';
import JobInformationEntry from '@/pages/CompanyManagement/Job/detail';

const localVue = createLocalVue();

describe('TEST SCREEN SELECT ENTRY HR INFORMATION', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(JobInformationEntry);
  });

  afterEach(() => {
    wrapper.destroy();
  });

  test('TEST RENDER COMPONENT SELECT ENTRY HR DETAIL', () => {
    const wrapper = shallowMount(JobInformationEntry, {
      store,
      router,
      localVue,
    });

    const OVERLAY = {
      opacity: 1,
      show: false,
      blur: '1rem',
      rounded: 'sm',
      variant: 'light',
    };

    expect(wrapper.vm.overlay).toStrictEqual(OVERLAY);

    wrapper.destroy();
  });

  // Test Case 1: Test handleConfirmLeavePage function (Mocking navigation)
  it('NAVIGATES TO PREVIOUS PAGE WHEN LEAVING THE PAGE', () => {
    wrapper.setData({ isShowModalWarning: true });

    const pushSpy = jest.spyOn(wrapper.vm.$router, 'push');

    wrapper.vm.handleConfirmLeavePage();

    expect(pushSpy).toHaveBeenCalledWith({ path: '/job-search/select-entry-hr' });

    pushSpy.mockRestore();
  });

  // Test Case 2: Test handleFetchDataFromProps function with filled data
  it('CORRECTLY SETS V_ITEMS AND ERROR ARRAYS BASED ON PROPS WITH FILLED DATA', () => {
    const list_hr_information = [{ id: 1, full_name: 'John Doe' /* other fields */ }];
    const is_filled_data = true;

    wrapper.setProps({ list_hr_information, is_filled_data });
    wrapper.vm.handleFetchDataFromProps();

    expect(wrapper.vm.vItems).toEqual(list_hr_information);
    expect(wrapper.vm.error).toEqual([{ file_motivation: true }]);
  });

  // Test Case 3: Test handleFetchDataFromProps function with empty data
  it('CORRECTLY SETS VITEMS AND ERROR ARRAYS BASED ON PROPS WITH EMPTY DATA', () => {
    const list_selected_hr = [{ id: 1, full_name: 'Jane Smith' /* other fields */ }];
    const is_filled_data = false;

    wrapper.setProps({ list_selected_hr, is_filled_data });
    wrapper.vm.handleFetchDataFromProps();

    expect(wrapper.vm.vItems).toEqual(list_selected_hr);
    expect(wrapper.vm.error).toEqual([{ file_motivation: false }]);
  });

  // Test Case 4: Test navigateToJobEntry function when data is not filled
  it('NAVIGATES TO JOB ENTRY PAGE WHEN DATA IS NOT FILLED', () => {
    const pushSpy = jest.spyOn(wrapper.vm.$router, 'push');

    wrapper.vm.navigateToJobEntry();

    expect(pushSpy).toHaveBeenCalledWith({ path: '/job-search/select-entry-hr' });

    pushSpy.mockRestore();
  });

  // Test Case 5: Test navigateToJobConfirmation function with valid data
  it('NAVIGATES TO JOB CONFIRMATION PAGE WHEN DATA IS VALID', async() => {
    const handleValidateDataMock = jest.fn().mockReturnValue(true);
    wrapper.vm.handleValidateData = handleValidateDataMock;

    const dispatchMock = jest.spyOn(wrapper.vm.$store, 'dispatch');
    const pushSpy = jest.spyOn(wrapper.vm.$router, 'push');

    await wrapper.vm.navigateToJobConfirmation();

    expect(handleValidateDataMock).toHaveBeenCalled();
    expect(dispatchMock).toHaveBeenCalledWith('job/setListHrInformation', wrapper.vm.vItems);
    expect(dispatchMock).toHaveBeenCalledWith('job/setIsFilledData', true);
    expect(pushSpy).toHaveBeenCalledWith({ name: 'JobConfirmationEntry' });

    dispatchMock.mockRestore();
    pushSpy.mockRestore();
  });

  // Test Case 6: Test handleAddFileInput function (Add file to item)
  it('CORRECTLY ADDS A FILE INPUT TO THE SPECIFIED ITEM', () => {
    const idToAddFile = 1;
    const initialVItems = [{ id: 1, file_others: [] }, { id: 2, file_others: [] }];
    wrapper.setData({ vItems: initialVItems });

    wrapper.vm.handleAddFileInput(idToAddFile);

    const updatedVItems = [{ id: 1, file_others: [{ file: null, file_name: '', file_size: '', file_id: null }] }, { id: 2, file_others: [] }];
    expect(wrapper.vm.vItems).toEqual(updatedVItems);
  });

  // Test Case 7: Test openFileSelect function for different types
  it('TRIGGERS FILE INPUT CLICK FOR DIFFERENT FILE TYPES', () => {
    const clickSpy = jest.spyOn(wrapper.vm.$refs.fileInputMotivation0[0], 'click');

    wrapper.vm.openFileSelect(1, 0);

    expect(clickSpy).toHaveBeenCalled();

    clickSpy.mockRestore();
  });

  // Test Case 8: Test handleFileSelect function for motivation file
  it('CORRECTLY HANDLES SELECTION OF MOTIVATION FILE', async() => {
    const selectedFile = new File(['Test Content'], 'test.txt', { type: 'text/plain' });

    const handleUploadSingleFileMock = jest.fn().mockReturnValue({ id: 123, status: true });
    wrapper.vm.handleUploadSingleFile = handleUploadSingleFileMock;

    const tempData = [{ id: 1, file_motivation: { file: null, file_name: '', file_id: null }}];
    wrapper.setData({ vItems: tempData });

    const event = { target: { files: [selectedFile] }};
    await wrapper.vm.handleFileSelect(1, event, 1, null, 0);

    expect(handleUploadSingleFileMock).toHaveBeenCalledWith(selectedFile, 1);
    expect(wrapper.vm.vItems[0].file_motivation.file).toEqual(selectedFile);
    expect(wrapper.vm.vItems[0].file_motivation.file_name).toContain('test.txt');
    expect(wrapper.vm.vItems[0].file_motivation.file_id).toEqual(123);
  });

  // Test Case 9: Test handleFileSelect function for recommendation file
  it('CORRECTLY HANDLES SELECTION OF RECOMMENDATION FILE', async() => {
    const selectedFile = new File(['Test Content'], 'test.pdf', { type: 'application/pdf' });

    const handleUploadSingleFileMock = jest.fn().mockReturnValue({ id: 456, status: true });
    wrapper.vm.handleUploadSingleFile = handleUploadSingleFileMock;

    const tempData = [{ id: 1, file_recommendation: { file: null, file_name: '', file_id: null }}];
    wrapper.setData({ vItems: tempData });

    const event = { target: { files: [selectedFile] }};
    await wrapper.vm.handleFileSelect(2, event, 1, null, 0);

    expect(handleUploadSingleFileMock).toHaveBeenCalledWith(selectedFile, 2);
    expect(wrapper.vm.vItems[0].file_recommendation.file).toEqual(selectedFile);
    expect(wrapper.vm.vItems[0].file_recommendation.file_name).toContain('test.pdf');
    expect(wrapper.vm.vItems[0].file_recommendation.file_id).toEqual(456);
  });

  // Test Case 10: Test handleFileSelect function for other file
  it('CORRECTLY HANDLES SELECTION OF OTHER FILE', async() => {
    const selectedFile = new File(['Test Content'], 'test.docx', { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });

    const handleUploadSingleFileMock = jest.fn().mockReturnValue({ id: 789, status: true });
    wrapper.vm.handleUploadSingleFile = handleUploadSingleFileMock;

    const tempData = [{ id: 1, file_others: [{ file: null, file_name: '', file_id: null }] }];
    wrapper.setData({ vItems: tempData });

    const event = { target: { files: [selectedFile] }};
    await wrapper.vm.handleFileSelect(3, event, 1, 0, 0);

    expect(handleUploadSingleFileMock).toHaveBeenCalledWith(selectedFile, 3);
    expect(wrapper.vm.vItems[0].file_others[0].file).toEqual(selectedFile);
    expect(wrapper.vm.vItems[0].file_others[0].file_name).toContain('test.docx');
    expect(wrapper.vm.vItems[0].file_others[0].file_id).toEqual(789);
  });

  // Test Case 11: Test validateFileSize function for valid file size
  it('VALIDATES FILE SIZE WITHIN THE LIMIT', () => {
    const validFileSize = 1024 * 1024 * 2; // 2 MB
    const result = wrapper.vm.validateFileSize(validFileSize);
    expect(result).toBe(true);
  });

  // Test Case 12: Test validateFileSize function for exceeding file size
  it('VALIDATES EXCEEDING FILE SIZE', () => {
    const exceedingFileSize = 1024 * 1024 * 4; // 4 MB
    const result = wrapper.vm.validateFileSize(exceedingFileSize);
    expect(result).toBe(false);
  });

  // Test Case 13: Test bytesToMB function
  it('CONVERTS BYTES TO MEGABYTES CORRECTLY', () => {
    const bytes = 1024 * 1024; // 1 MB
    const megabytes = wrapper.vm.bytesToMB(bytes);
    expect(megabytes).toBe('1.00');
  });

  // Test Case 14: Test truncateString function for short string
  it('DOES NOT TRUNCATE A SHORT STRING', () => {
    const shortString = 'Hello';
    const truncatedString = wrapper.vm.truncateString(shortString);
    expect(truncatedString).toBe(shortString);
  });

  // Test Case 15: Test truncateString function for long string
  it('TRUNCATES A LONG STRING', () => {
    const longString = 'ThisIsALongStringThatNeedsToBeTruncated';
    const truncatedString = wrapper.vm.truncateString(longString);
    expect(truncatedString).toBe('ThisIsALon...');
  });
});

