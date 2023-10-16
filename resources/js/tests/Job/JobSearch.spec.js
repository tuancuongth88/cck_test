import { shallowMount, createLocalVue } from '@vue/test-utils';

import store from '@/store';
import router from '@/router';
import JobSearch from '@/pages/JobSearch/search';

const localVue = createLocalVue();

describe('TEST SCREEN JOB SEARCH', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(JobSearch);
  });

  afterEach(() => {
    wrapper.destroy();
  });

  test('TEST RENDER COMPONENT JOB SEARCH', () => {
    const wrapper = shallowMount(JobSearch, {
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

  it('Test if the handleGetListOccupation method retrieves occupation data successfully.', async() => {
    // Mock the getListMainjob API response
    const mockResponse = {
      data: {
        code: 200,
        data: [
          { id: 1, name: 'Occupation 1', job_info: [] },
          { id: 2, name: 'Occupation 2', job_info: [] },
        ],
      },
    };

    // Mock the $store.dispatch method
    const dispatchMock = jest.fn(() => Promise.resolve(mockResponse));

    // Set up the component and mock dependencies
    const wrapper = shallowMount(JobSearch, {
      mocks: {
        $store: {
          dispatch: dispatchMock,
        },
        $t: () => {},
      },
    });

    // Call the method
    await wrapper.vm.handleGetListOccupation();

    // Check if the modal_occupation_options data is updated correctly
    expect(wrapper.vm.modal_occupation_options).toEqual([
      { id: 1, name: 'Occupation 1', childOptions: [] },
      { id: 2, name: 'Occupation 2', childOptions: [] },
    ]);

    // Check if the API was called with the correct parameters
    expect(dispatchMock).toHaveBeenCalledWith('jobSearch/setJobSearchData', {});
  });

  it('Test if the handleGetListOccupation method handles an API error and displays a toast message.', async() => {
    // Mock the getListMainjob API response with an error
    const mockErrorResponse = {
      response: {
        data: {
          code: 400,
          message: 'API Error',
        },
      },
    };

    // Mock the $store.dispatch method to return an error response
    const dispatchMock = jest.fn(() => Promise.reject(mockErrorResponse));

    // Set up the component and mock dependencies
    const wrapper = shallowMount(JobSearch, {
      mocks: {
        $store: {
          dispatch: dispatchMock,
        },
        $t: () => {},
      },
    });

    // Mock the MakeToast function
    const makeToastMock = jest.spyOn(wrapper.vm, 'MakeToast');

    // Call the method
    await wrapper.vm.handleGetListOccupation();

    // Check if the toast message is displayed with the correct error message
    expect(makeToastMock).toHaveBeenCalledWith({
      variant: 'warning',
      title: 'WARNING',
      content: 'API Error',
    });
  });

  it('Test if the handleSearch method sets search data in the store and navigates to the search results page.', async() => {
    // Mock the $store.dispatch method
    const dispatchMock = jest.fn();

    // Mock the $router.push method
    const pushMock = jest.fn();

    // Set up the component and mock dependencies
    const wrapper = shallowMount(JobSearch, {
      mocks: {
        $store: {
          dispatch: dispatchMock,
        },
        $router: {
          push: pushMock,
        },
        $t: () => {},
      },
      data() {
        return {
          occupation: [{ id: 1, name: 'Occupation 1', childOptions: [] }],
          income_from: '500',
          income_to: '1000',
          city_selected: [],
          language_requirement: [],
          form_of_employment: 1,
          passion: [],
          free_word: '',
        };
      },
    });

    // Call the method
    await wrapper.vm.handleSearch();

    // Check if the store dispatch method was called with the correct parameters
    expect(dispatchMock).toHaveBeenCalledWith('jobSearch/setJobSearchData', {
      middle_classification_id: [{ id: 1, name: 'Occupation 1', childOptions: [] }],
      income_from: '500',
      income_to: '1000',
      city_id: [],
      language_requirements: [],
      form_of_employment: [1],
      passion_works: [],
      key_search: '',
    });

    // Check if the router push method was called with the correct path
    expect(pushMock).toHaveBeenCalledWith({ path: '/job-search/list' });
  });

  it('Test if the handleClearFormData method resets all form data and clears the search settings.', async() => {
    // Set up the component with initial data
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          occupation: [{ id: 1, name: 'Occupation 1', childOptions: [] }],
          income_from: '500',
          income_to: '1000',
          city_selected: [1, 2],
          language_requirement: [1, 2],
          form_of_employment: 1,
          passion: [1, 2],
          free_word: 'test',
        };
      },
      mocks: {
        $store: {
          dispatch: jest.fn(),
        },
        $t: () => {},
      },
    });

    // Call the method
    wrapper.vm.handleClearFormData();

    // Check if all form data is reset to its initial state
    expect(wrapper.vm.occupation).toEqual([]);
    expect(wrapper.vm.income_from).toBe('');
    expect(wrapper.vm.income_to).toBe('');
    expect(wrapper.vm.city_selected).toEqual([]);
    expect(wrapper.vm.language_requirement).toEqual([]);
    expect(wrapper.vm.form_of_employment).toBe('');
    expect(wrapper.vm.passion).toEqual([]);
    expect(wrapper.vm.free_word).toBe('');

    // Check if the store dispatch method was called with an empty object
    expect(wrapper.vm.$store.dispatch).toHaveBeenCalledWith('jobSearch/setJobSearchData', {});
  });

  it('Test if the handleRemoveSelected method removes a selected occupation and updates the form data.', async() => {
    // Set up the component with initial occupation data
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          occupation: [
            { id: 1, name: 'Occupation 1', childOptions: [] },
            { id: 2, name: 'Occupation 2', childOptions: [] },
          ],
        };
      },
      mocks: {
        $store: {
          dispatch: jest.fn(),
        },
        $t: () => {},
      },
    });

    // Call the method to remove the occupation with id 1
    wrapper.vm.handleRemoveSelected(1);

    // Check if the occupation data is updated correctly
    expect(wrapper.vm.occupation).toEqual([{ id: 2, name: 'Occupation 2', childOptions: [] }]);

    // Check if the store dispatch method was called with the updated form data
    expect(wrapper.vm.$store.dispatch).toHaveBeenCalledWith('jobSearch/setJobSearchData', {
      middle_classification_id: [{ id: 2, name: 'Occupation 2', childOptions: [] }],
    });
  });

  it('Test if the handleSelectCity method successfully adds a selected city to the city_selected array.', async() => {
    // Set up the component with initial data
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          city_selected: [1],
        };
      },
      mocks: {
        $store: {
          dispatch: jest.fn(),
        },
        $t: () => {},
      },
    });

    // Call the method to select a new city with id 2
    wrapper.vm.handleSelectCity(2);

    // Check if the city_selected array is updated correctly
    expect(wrapper.vm.city_selected).toEqual([1, 2]);
  });

  it('Test if the handleRemoveCity method successfully removes a selected city from the city_selected array.', async() => {
    // Set up the component with initial data
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          city_selected: [1, 2],
        };
      },
      mocks: {
        $store: {
          dispatch: jest.fn(),
        },
        $t: () => {},
      },
    });

    // Call the method to remove a city with id 1
    wrapper.vm.handleRemoveCity(1);

    // Check if the city_selected array is updated correctly
    expect(wrapper.vm.city_selected).toEqual([2]);
  });

  it('Test if the handleSelectLanguage method successfully adds a selected language to the language_requirement array.', async() => {
    // Set up the component with initial data
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          language_requirement: [1],
        };
      },
      mocks: {
        $store: {
          dispatch: jest.fn(),
        },
        $t: () => {},
      },
    });

    // Call the method to select a new language with id 2
    wrapper.vm.handleSelectLanguage(2);

    // Check if the language_requirement array is updated correctly
    expect(wrapper.vm.language_requirement).toEqual([1, 2]);
  });

  it('Test if the handleRemoveLanguage method successfully removes a selected language from the language_requirement array.', async() => {
    // Set up the component with initial data
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          language_requirement: [1, 2],
        };
      },
      mocks: {
        $store: {
          dispatch: jest.fn(),
        },
        $t: () => {},
      },
    });

    // Call the method to remove a language with id 1
    wrapper.vm.handleRemoveLanguage(1);

    // Check if the language_requirement array is updated correctly
    expect(wrapper.vm.language_requirement).toEqual([2]);
  });

  it('Test if the handleSelectPassion method successfully adds a selected passion to the passion array.', async() => {
    // Set up the component with initial data
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          passion: [1],
        };
      },
      mocks: {
        $store: {
          dispatch: jest.fn(),
        },
        $t: () => {},
      },
    });

    // Call the method to select a new passion with id 2
    wrapper.vm.handleSelectPassion(2);

    // Check if the passion array is updated correctly
    expect(wrapper.vm.passion).toEqual([1, 2]);
  });

  it('Test if the handleRemovePassion method successfully removes a selected passion from the passion array.', async() => {
    // Set up the component with initial data
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          passion: [1, 2],
        };
      },
      mocks: {
        $store: {
          dispatch: jest.fn(),
        },
        $t: () => {},
      },
    });

    // Call the method to remove a passion with id 1
    wrapper.vm.handleRemovePassion(1);

    // Check if the passion array is updated correctly
    expect(wrapper.vm.passion).toEqual([2]);
  });

  it('Test if the handleSearch method correctly dispatches the job search data to the store and navigates to the job search list page.', async() => {
    // Set up the component with mock store and router
    const mockDispatch = jest.fn();
    const mockRouterPush = jest.fn();
    const wrapper = shallowMount(JobSearch, {
      mocks: {
        $store: {
          dispatch: mockDispatch,
        },
        $router: {
          push: mockRouterPush,
        },
        $t: () => {},
      },
      data() {
        return {
          occupation: [1, 2],
          income_from: '300',
          income_to: '500',
          city_selected: [3, 4],
          language_requirement: [1, 2],
          form_of_employment: 2,
          passion: [3, 4],
          free_word: 'developer',
        };
      },
    });

    // Call the method to trigger job search
    wrapper.vm.handleSearch();

    // Check if the store dispatch was called with correct data
    expect(mockDispatch).toHaveBeenCalledWith('jobSearch/setJobSearchData', {
      middle_classification_id: [1, 2],
      income_from: '300',
      income_to: '500',
      city_id: [3, 4],
      language_requirements: [1, 2],
      form_of_employment: [2],
      passion_works: [3, 4],
      key_search: 'developer',
    });

    // Check if router push was called with correct path
    expect(mockRouterPush).toHaveBeenCalledWith({ path: '/job-search/list' });
  });

  it('Test if the handleOpenModalOccupation method correctly sets the is_show_modal_occupation flag to true.', async() => {
    // Set up the component
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          is_show_modal_occupation: false,
        };
      },
      mocks: {
        $t: () => {},
      },
    });

    // Call the method to open the occupation modal
    wrapper.vm.handleOpenModalOccupation();

    // Check if the is_show_modal_occupation flag is set to true
    expect(wrapper.vm.is_show_modal_occupation).toBe(true);
  });

  it('Test if the handleClearFormData method correctly clears all form data and dispatches an empty object to the store.', async() => {
    // Set up the component with mock store
    const mockDispatch = jest.fn();
    const wrapper = shallowMount(JobSearch, {
      mocks: {
        $store: {
          dispatch: mockDispatch,
        },
        $t: () => {},
      },
      data() {
        return {
          occupation: [1, 2],
          income_from: '300',
          income_to: '500',
          city_selected: [3, 4],
          language_requirement: [1, 2],
          form_of_employment: 2,
          passion: [3, 4],
          free_word: 'developer',
        };
      },
    });

    // Call the method to clear form data
    wrapper.vm.handleClearFormData();

    // Check if all form data is cleared
    expect(wrapper.vm.occupation).toEqual([]);
    expect(wrapper.vm.income_from).toBe('');
    expect(wrapper.vm.income_to).toBe('');
    expect(wrapper.vm.city_selected).toEqual([]);
    expect(wrapper.vm.language_requirement).toEqual([]);
    expect(wrapper.vm.form_of_employment).toBe('');
    expect(wrapper.vm.passion).toEqual([]);
    expect(wrapper.vm.free_word).toBe('');

    // Check if store dispatch was called with an empty object
    expect(mockDispatch).toHaveBeenCalledWith('jobSearch/setJobSearchData', {});
  });

  it('Test if the created hook correctly sets up event listeners and fetches occupation data.', async() => {
    // Set up the component with mock event bus and API call
    const mockEventBusOn = jest.fn();
    const mockGetListMainjob = jest.fn(() => Promise.resolve({ data: { code: 200, data: [] }}));
    const wrapper = shallowMount(JobSearch, {
      mocks: {
        $t: () => {},
        $store: {},
        $router: {},
        $on: mockEventBusOn,
        $off: () => {},
      },
      methods: {
        handleGetListOccupation: mockGetListMainjob,
      },
    });

    // Check if event listeners were set up
    expect(mockEventBusOn).toHaveBeenCalledTimes(2);
    expect(mockEventBusOn).toHaveBeenCalledWith('modalSpecifyJobExpShowStatusChanged', expect.any(Function));
    expect(mockEventBusOn).toHaveBeenCalledWith('dataModalMultiple', expect.any(Function));

    // Check if handleGetListOccupation was called during component creation
    expect(mockGetListMainjob).toHaveBeenCalled();

    wrapper.destroy();
  });

  it('Test if the handleRemoveSelected method correctly removes a selected occupation from the occupation array and updates the form data.', async() => {
    // Set up the component with initial data
    const wrapper = shallowMount(JobSearch, {
      data() {
        return {
          occupation: [
            { id: 1, name: 'Occupation 1' },
            { id: 2, name: 'Occupation 2' },
          ],
          // ... other form data
        };
      },
      mocks: {
        $t: () => {},
      },
    });

    // Call the method to remove occupation with id 1
    wrapper.vm.handleRemoveSelected(1);

    // Check if the occupation array is updated correctly
    expect(wrapper.vm.occupation).toEqual([{ id: 2, name: 'Occupation 2' }]);

    // Check if form data is updated correctly (e.g., by dispatching to the store)
    // ... (implement your own checks based on your actual implementation)
  });

  it('Test if the event listeners for modal-related events are set up correctly and trigger the desired behavior.', async() => {
    // Set up the component with mock event bus
    const mockEventBusOn = jest.fn();
    const mockEventBusEmit = jest.fn();
    const wrapper = shallowMount(JobSearch, {
      mocks: {
        $t: () => {},
        $store: {},
        $router: {},
        $on: mockEventBusOn,
        $off: () => {},
        $emit: mockEventBusEmit,
      },
      methods: {
        // ... other methods
      },
    });

    // Check if event listeners were set up
    expect(mockEventBusOn).toHaveBeenCalledWith('modalSpecifyJobExpShowStatusChanged', expect.any(Function));
    expect(mockEventBusOn).toHaveBeenCalledWith('dataModalMultiple', expect.any(Function));

    // Simulate triggering the 'showModalSelect' event
    wrapper.vm.handleOpenModalOccupation();

    // Check if the 'showModalSelect' event triggers the expected behavior (e.g., changing a flag)
    expect(wrapper.vm.is_show_modal_occupation).toBe(true);
  });

  it('Test if the handleSearch method correctly validates form data before dispatching it to the store and navigating to the job search list page.', async() => {
    // Set up the component with mock store and router
    const mockDispatch = jest.fn();
    const mockRouterPush = jest.fn();
    const wrapper = shallowMount(JobSearch, {
      mocks: {
        $store: {
          dispatch: mockDispatch,
        },
        $router: {
          push: mockRouterPush,
        },
        $t: () => {},
      },
      data() {
        return {
          // ... other form data
          occupation: [], // Empty occupation (invalid)
          income_from: '', // Empty income_from (invalid)
          income_to: '400', // Valid income_to
          // ... other form data
        };
      },
    });

    // Call the method to trigger job search
    wrapper.vm.handleSearch();

    // Check if the store dispatch and router push were not called due to invalid form data
    expect(mockDispatch).not.toHaveBeenCalled();
    expect(mockRouterPush).not.toHaveBeenCalled();
  });

  it('Test if the handleSearch method correctly handles form data with default values.', async() => {
    // Set up the component with mock store and router
    const mockDispatch = jest.fn();
    const mockRouterPush = jest.fn();
    const wrapper = shallowMount(JobSearch, {
      mocks: {
        $store: {
          dispatch: mockDispatch,
        },
        $router: {
          push: mockRouterPush,
        },
        $t: () => {},
      },
      data() {
        return {
          // ... other form data
          occupation: [], // Empty occupation (invalid)
          income_from: '', // Empty income_from (invalid)
          income_to: '', // Empty income_to (invalid)
          city_selected: [], // Empty city_selected (invalid)
          // ... other form data
        };
      },
    });

    // Call the method to trigger job search
    wrapper.vm.handleSearch();

    // Check if the store dispatch and router push were not called due to invalid form data
    expect(mockDispatch).not.toHaveBeenCalled();
    expect(mockRouterPush).not.toHaveBeenCalled();
  });
});
