package main

type Dao interface {
	find(id uint64) *Presentation
	persist(*Presentation)
}

type InMemoryDao struct {
	data map[uint64]Presentation
}

func newInMemoryDao() *InMemoryDao {
	return &InMemoryDao{
		data: make(map[uint64]Presentation),
	}
}

func (dao *InMemoryDao) find(userId uint64) *Presentation {
	presentation := dao.data[userId]
	return &presentation
}

func (dao *InMemoryDao) persist(p *Presentation) {
	dao.data[p.UserId] = *p
}
