



test('toBeTssruthy !', ()=>{
    const xx = jest.fn(()=>1);
    xx(1)
    expect(xx).toHaveBeenCalledWith("1");

})
