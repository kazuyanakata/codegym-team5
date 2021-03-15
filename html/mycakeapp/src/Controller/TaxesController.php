<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Taxes Controller
 *
 * @property \App\Model\Table\TaxesTable $Taxes
 *
 * @method \App\Model\Entity\Tax[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TaxesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $taxes = $this->paginate($this->Taxes);

        $this->set(compact('taxes'));
    }

    /**
     * View method
     *
     * @param string|null $id Tax id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $tax = $this->Taxes->get($id, [
            'contain' => [],
        ]);

        $this->set('tax', $tax);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $tax = $this->Taxes->newEntity();
        if ($this->request->is('post')) {
            $tax = $this->Taxes->patchEntity($tax, $this->request->getData());
            if ($this->Taxes->save($tax)) {
                $this->Flash->success(__('The tax has been saved.'));

                return $this->redirect(['action' => 'index', '_ssl' => true]);
            }
            $this->Flash->error(__('The tax could not be saved. Please, try again.'));
        }
        $this->set(compact('tax'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Tax id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $tax = $this->Taxes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tax = $this->Taxes->patchEntity($tax, $this->request->getData());
            if ($this->Taxes->save($tax)) {
                $this->Flash->success(__('The tax has been saved.'));

                return $this->redirect(['action' => 'index', '_ssl' => true]);
            }
            $this->Flash->error(__('The tax could not be saved. Please, try again.'));
        }
        $this->set(compact('tax'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Tax id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tax = $this->Taxes->get($id);
        if ($this->Taxes->delete($tax)) {
            $this->Flash->success(__('The tax has been deleted.'));
        } else {
            $this->Flash->error(__('The tax could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index', '_ssl' => true]);
    }
}
