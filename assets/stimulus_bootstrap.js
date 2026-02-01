import { Application } from '@hotwired/stimulus';
import CandidateResearchController from './controllers/candidate-research_controller.js';

//const app = startStimulusApp();
const app = Application.start();
// register any custom, 3rd party controllers here
app.register('candidate-research', CandidateResearchController);
