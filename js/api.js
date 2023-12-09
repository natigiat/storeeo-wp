const API = "http://localhost:3001";

// Function to perform a generic HTTP request
async function fetchData(url, method, data = null) {
  try {
    const options = {
      method: method,
      headers: {
        "Content-Type": "application/json",
      },
      body: data ? JSON.stringify(data) : null,
    };

    const response = await fetch(url, options);
    const result = await response.json();
    return result;
  } catch (error) {
    console.error(`Error in fetchData for ${url}`, error);
    throw error; // Re-throw the error to allow the calling function to handle it
  }
}

// Function to create a new record
async function createRecord(route, data) {
  try {
    const url = `${API}/${route}/`;
    const method = "POST";
    return await fetchData(url, method, data);
  } catch (error) {
    console.error(`Error in createRecord for ${API}`, error);
    throw error;
  }
}

// Function to update an existing record
async function updateRecord(id, data) {
  try {
    const url = `${API}/${id}`;
    const method = "PUT";
    return await fetchData(url, method, data);
  } catch (error) {
    console.error(`Error in updateRecord for ${API}/${id}`, error);
    throw error;
  }
}

// Function to get a record by ID
async function getRecord(route, id = null) {
  try {
    const url = id ? `${API}/${route}/${id}` : `${API}/${route}/`;
    const method = "GET";
    return await fetchData(url, method);
  } catch (error) {
    console.error(`Error in getRecord for ${API}/${id}`, error);
    throw error;
  }
}

// Function to delete a record by ID
async function deleteRecord(id) {
  try {
    const url = `${API}/${id}`;
    const method = "DELETE";
    return await fetchData(url, method);
  } catch (error) {
    console.error(`Error in deleteRecord for ${API}/${id}`, error);
    throw error;
  }
}
