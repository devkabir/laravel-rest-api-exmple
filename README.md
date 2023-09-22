# Create a simple task management app 
The frontend and backend need to be separate apps.
## Features to have:

### Task Management:
- [x] Create Task: Users can create tasks with a name and description.
- [x] Assign Multiple Users: Tasks can be assigned to multiple users.
- [x] View Tasks: Any user in the app can view the list of tasks.
  
### Task Updates:
- [] Update Task Details: Only the creator of the task or the assigned users can
  update the task, including the name, description, and
  assignment/unassignment of users.
- [] Comment on Task: Only the creator of the task or the assigned users can
  comment on a task. 
  
### Frontend:
- Choose between React/Vue.
- Use any state management library of your choice.
- Apply Tailwind CSS for styling.
- Implement user registration and login features (no need for "forgot
  password" or password reset pages).
- Fetch information from the backend via the API.
  Note: this is not a designer test so the frontend does not have to look "good",
  but of course bonus points if you can make it look appealing.
### Backend:
- User Registration API: Implement user registration with email verification.
- Create Task API: Allow users to create tasks.
- Assignment Email: Send an email notification to the assigned user when a
  task is assigned to them.
- Update Task API: Enable task updates by the creator or assigned users,
  including task details and user assignments.
- Delete Task API: Allow users to delete tasks.
- Create Comment API: Enable users to comment on tasks.
- Comment Notification: Send email notifications to the task creator and all
  assigned users when a new comment is added.
### Things to Consider:
- Consider writing tests
- Implement proper authentication and authorization mechanisms to ensure
  that only authorized users can perform certain actions.
- Implement validation for user input and handle potential errors gracefully.
- Use appropriate database relationships to associate tasks with users and
  comments.
- Ensure the application's security by protecting against common
  vulnerabilities.
- Consider implementing pagination or infinite scrolling for task lists if the
  number of tasks becomes substantial.
- Use modern coding standards and best practices for both frontend and
  backend development.
- Provide clear instructions on how to run and test the applications.
- Include a brief README outlining the application's architecture, technologies
  used, and any setup or deployment instructions.
